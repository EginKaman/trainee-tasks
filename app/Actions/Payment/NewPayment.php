<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Http\Requests\StorePaymentRequest;
use App\Models\{Order, Payment};
use Illuminate\Http\JsonResponse;

class NewPayment
{
    public function create(StorePaymentRequest $request): JsonResponse
    {
        $order = Order::find($request->validated('order_id'));

        $payment = new Payment([
            'method' => 'stripe',
            'currency' => 'usd',
            'amount' => $order->amount,
        ]);

        $payment->user()->associate(auth('api')->user());
        $payment->order()->associate($request->validated('order_id'));

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
}
