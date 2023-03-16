<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Payment\{NewPayment, NewRefund};
use App\Enum\OrderStatus;
use App\Exceptions\{OrderNotPayedException, OrderRefundedException, UnknownPaymentMethodException};
use App\Http\Requests\{RefundPaymentRequest, StorePaymentRequest};
use App\Models\{Card, Payment, PaymentHistory, Subscription, SubscriptionUser, User};
use App\Services\Payment\{Webhook};
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Support\{Carbon, Str};
use Srmklive\PayPal\Facades\PayPal;
use Stripe\{Exception\SignatureVerificationException, Webhook as StripeWebhook};
use Symfony\Component\HttpKernel\Exception\HttpException;
use UnexpectedValueException;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, NewPayment $payment): JsonResponse
    {
        try {
            $response = $payment->create(auth('api')->user(), $request->validated());
        } catch (UnknownPaymentMethodException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json($response);
    }

    public function refund(RefundPaymentRequest $request, NewRefund $refund): JsonResponse
    {
        try {
            $refund->refund($request->validated('order_id'));
        } catch (HttpException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        }

        return response()->json([
            'message' => __('Refund success'),
        ]);
    }

    public function webhook(Request $request, string $method): JsonResponse|Response
    {
        try {
            $webhook = new Webhook($method);
        } catch (UnknownPaymentMethodException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
        $webhook->validateSignature($request);

        if ($method === 'paypal') {
            if (!isset($request->resource['supplementary_data'])) {
                return response()->noContent();
            }

            $payment = Payment::where(
                'method_id',
                $request->resource['supplementary_data']['related_ids']['order_id']
            )->where('method', 'paypal')->first();

            if ($payment === null) {
                return response()->noContent();
            }

            $payment->status = $request->resource['status'];

            $payment->history()->save(new PaymentHistory([
                'status' => $request->resource['status'],
            ]));

            return response()->json($request);
        }

        try {
            $event = StripeWebhook::constructEvent(
                file_get_contents('php://input'),
                $request->server('HTTP_STRIPE_SIGNATURE'),
                config
                (
                    'services.stripe.webhook_secret'
                )
            );
        } catch (UnexpectedValueException|SignatureVerificationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        if (Str::startsWith($event->type, 'payment_intent.')) {
            /** @phpstan-ignore-next-line */
            $paymentIntent = $event->data->object;

            $payment = Payment::with('order')
                ->where('method_id', $paymentIntent->id)
                ->where('method', 'stripe')->first();

            if ($payment === null) {
                return response()->noContent();
            }

            if ($paymentIntent->status === 'success') {
                $payment->order->status = OrderStatus::PaymentSuccess;
                $payment->order->save();
            }
            $payment->status = $paymentIntent->status;
            $payment->client_secret = $paymentIntent->client_secret;

            $payment->save();

            $payment->history()->save(new PaymentHistory([
                'status' => $paymentIntent->status,
            ]));

            return response()->json([
                'message' => 'success',
            ]);
        }
        if ($event->type === 'charge.refunded') {
            /** @phpstan-ignore-next-line */
            $refund = $event->data->object;

            $payment = Payment::where('method_id', $refund->payment_intent)
                ->where('method', 'stripe')->first();

            $payment->status = OrderStatus::Refunded;
            $payment->client_secret = $refund->client_secret;

            $payment->save();

            $payment->history()->save(new PaymentHistory([
                'status' => OrderStatus::Refunded,
            ]));

            return response()->json([
                'message' => 'success',
            ]);
        }
        if ($event->type === 'payment_method.attached') {
            /** @phpstan-ignore-next-line */
            $paymentMethod = $event->data->object;

            $user = User::where('stripe_id', $paymentMethod->customer)->first();

            if ($user) {
                $card = new Card([
                    'fingerprint' => $paymentMethod->id,
                    'type' => $paymentMethod->card->brand,
                    'last_numbers' => $paymentMethod->card->last4,
                ]);
                $card->user()->associate($user);
                $card->save();
            }

            return response()->json([
                'message' => 'success',
            ]);
        }
        if (Str::startsWith($event->type, 'customer.subscription.')) {
            /** @phpstan-ignore-next-line */
            $data = $event->data->object;
            $user = User::where('stripe_id', $data->customer)->first();

            $subscription = Subscription::where('stripe_id', $data->plan->id)->first();

            $user->subscriptions()->syncWithPivotValues($subscription, [
                'method_id' => $data->id,
                'status' => $data->status,
                'started_at' => Carbon::createFromTimestamp($data->start_date),
                'expired_at' => Carbon::createFromTimestamp($data->current_period_end),
            ]);

            return response()->json([
                'message' => 'success',
            ]);
        }

        return response()->json([
            'message' => 'Received unknown event type',
            'type' => $event->type,
        ], 400);
    }

    public function paymentSuccess(Request $request): JsonResponse
    {
        $provider = Paypal::setProvider();
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setRequestHeader('Authorization', 'Bearer ' . $token['access_token']);
        $provider->setRequestHeader('PayPal-Request-Id', Str::uuid()->toString());
        $response = $provider->capturePaymentOrder($request->get('token'));

        if (isset($response['errors'])) {
            return response()->json($response);
        }

        $payment = Payment::where('method_id', $response['id'])->where('method', 'paypal')->first();

        $payment->status = $response['status'];
        $payment->save();

        $payment->history()->save(new PaymentHistory([
            'status' => $response['status'],
        ]));

        return response()->json($response);
    }
}
