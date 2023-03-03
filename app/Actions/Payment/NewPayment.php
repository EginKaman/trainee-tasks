<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Http\Requests\StorePaymentRequest;
use App\Models\{Order, Payment};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Srmklive\PayPal\Facades\PayPal;

class NewPayment
{
    public function create(StorePaymentRequest $request): JsonResponse
    {
        $order = Order::find($request->validated('order_id'));

        $payment = new Payment([
            'method' => $request->validated('method'),
            'currency' => 'usd',
            'amount' => $order->amount,
        ]);

        $payment->user()->associate(auth('api')->user());
        $payment->order()->associate($request->validated('order_id'));

        if ($request->validated('method') === 'stripe') {
            $stripe = new \Stripe\StripeClient(config('services.stripe.api_secret'));

            $paymentIntent = $stripe->paymentIntents->create([
                //            'payment_method_types' => ['card'],
                'amount' => $payment->amount * 100,
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            $payment->method_id = $paymentIntent->id;
            $payment->client_secret = $paymentIntent->client_secret;

            $payment->save();

            return response()->json([
                'type_payment' => 'stripe',
                'payment_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'payment_method' => $paymentIntent->payment_method,
            ]);
        }
        $provider = Paypal::setProvider();
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setRequestHeader('Authorization', 'Bearer ' . $token['access_token']);
        $provider->setRequestHeader('PayPal-Request-Id', Str::uuid()->toString());
        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $payment->amount,
                    ],
                ],
            ],
            'application_context' => [
                'return_url' => url('api/v1/payments/paypal'),
                'cancel_url' => url('paypal/cancel'),
            ],
        ]);
        $payment->method_id = $order['id'];
        $payment->status = $order['status'];
        $payment->save();

        $redirectUrl = '';
        foreach ($order['links'] as $link) {
            if ($link['rel'] === 'approve') {
                $redirectUrl = $link['href'];
            }
        }

        return response()->json([
            'url' => $redirectUrl,
            'type_payment' => $payment->method,
            'payment_id' => $payment->method_id,
            'amount' => $payment->amount,
        ]);
    }
}
