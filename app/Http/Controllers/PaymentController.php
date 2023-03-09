<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Payment\NewPayment;
use App\Http\Requests\StorePaymentRequest;
use App\Models\{Payment, PaymentHistory, Subscription, SubscriptionUser, User};
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Support\{Carbon, Str};
use Srmklive\PayPal\Facades\PayPal;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, NewPayment $payment): JsonResponse
    {
        return $payment->create($request);
    }

    public function refund(Request $request): void
    {
    }

    public function webhook(Request $request, string $method): JsonResponse|Response
    {
        if ($method === 'paypal') {
            $provider = Paypal::setProvider();
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();
            $provider->setRequestHeader('Authorization', 'Bearer ' . $token['access_token']);
            $provider->setRequestHeader('PayPal-Request-Id', Str::uuid()->toString());

            $verify = $provider->verifyWebHook([
                'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
                'cert_url' => $request->header('PAYPAL-CERT-URL'),
                'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
                'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
                'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                'webhook_event' => $request->all(),
                'webhook_id' => config('paypal.' . config('paypal.mode') . '.webhook_id'),
            ]);
            if ($verify['verification_status'] === 'FAILURE') {
                return response()->json($verify, 400);
            }

            if (Str::startsWith($request->type, 'BILLING.SUBSCRIPTION.')) {
                $subscriptionUser = SubscriptionUser::where('method', 'paypal')->where(
                    'method_id',
                    $request->resource['id']
                )->with('user')->first();

                if ($subscriptionUser === null) {
                    return response()->noContent();
                }

                $user = $subscriptionUser->user;
                $subscription = $subscriptionUser->subscription;

                $startTime = Carbon::createFromFormat('Y-m-d\T\H:i:s\Z', $request->resource['start_time']);
                $user->subscriptions()->syncWithPivotValues($subscription, [
                    'method_id' => $request->resource['id'],
                    'status' => $request->resource['status'],
                    'started_at' => $startTime,
                    'expired_at' => $startTime->addMonth(),
                ]);

                return response()->noContent();
            }

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
            $event = \Stripe\Webhook::constructEvent(
                file_get_contents('php://input'),
                $request->server('HTTP_STRIPE_SIGNATURE'),
                config
                (
                    'services.stripe.webhook_secret'
                )
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        if (Str::startsWith($event->type, 'payment_intent.')) {
            /** @phpstan-ignore-next-line */
            $paymentIntent = $event->data->object;

            $payment = Payment::where('method_id', $paymentIntent->id)->where('method', 'stripe')->first();

            if ($payment === null) {
                return response()->noContent();
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

            $payment = Payment::where('method_id', $refund->payment_intent)->where('method', 'stripe')->first();

            $payment->status = 'refunded';
            $payment->client_secret = $refund->client_secret;

            $payment->save();

            $payment->history()->save(new PaymentHistory([
                'status' => 'refunded',
            ]));

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
