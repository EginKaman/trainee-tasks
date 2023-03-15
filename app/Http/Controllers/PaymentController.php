<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Payment\NewPayment;
use App\Enum\OrderStatus;
use App\Http\Requests\{RefundPaymentRequest, StorePaymentRequest};
use App\Models\{Card, Order, Payment, PaymentHistory, User};
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Support\{Str};
use Srmklive\PayPal\Facades\PayPal;
use Stripe\{StripeClient, Webhook};

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, NewPayment $payment): JsonResponse
    {
        $response = $payment->create(auth('api')->user(), $request->validated());

        return response()->json($response);
    }

    public function refund(RefundPaymentRequest $request): JsonResponse
    {
        $order = Order::find($request->validated('order_id'));
        if ($order->status === 'refunded') {
            return response()->json([
                'message' => __('Order has already refunded'),
            ], 409);
        }
        if ($order->payments()->count() === 0) {
            return response()->json([
                'message' => __("Order doesn't have payment"),
            ], 404);
        }

        $payment = $order->payments()->latest()->first();

        $order->status = OrderStatus::Refunded;
        $order->save();

        if ($payment->method === 'stripe') {
            $stripe = new StripeClient(config('services.stripe.api_secret'));
            $paymentIntent = $stripe->paymentIntents->retrieve($payment->method_id);
            $stripe->refunds->create([
                'charge' => $paymentIntent->latest_charge,
            ]);
        }

        $payment->status = 'refunded';
        $payment->save();

        return response()->json([
            'message' => __('Refund success'),
        ]);
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
            $event = Webhook::constructEvent(
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
