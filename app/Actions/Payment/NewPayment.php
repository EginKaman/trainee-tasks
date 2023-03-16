<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\DataTransferObjects\NewPaymentObject;
use App\Exceptions\UnknownPaymentMethodException;
use App\Models\{Card, Order, Payment, User};
use App\Services\Payment\Payment as PaymentClient;
use Illuminate\Support\Facades\DB;
use Throwable;

class NewPayment
{
    /**
     * @throws Throwable
     * @throws UnknownPaymentMethodException
     */
    public function create(User $user, array $request): array
    {
        $order = Order::find($request['order_id']);

        $payment = new Payment([
            'method' => $request['type_payment'],
            'currency' => 'USD',
            'amount' => $order->amount,
        ]);

        $payment->user()->associate($user);
        $payment->order()->associate($request['order_id']);

        $paymentClient = new PaymentClient($payment->method);

        if (isset($request['card_id'])) {
            $card = Card::find($request['card_id']);
        }

        $newPaymentObject = new NewPaymentObject(
            amount: $payment->amount,
            user: $user,
            saveCard: $request['save_card'],
            card: $card ?? null
        );

        $createdPaymentObject = $paymentClient->payment($newPaymentObject);

        $response = [
            'type_payment' => $createdPaymentObject->typePayment,
            'payment_id' => $createdPaymentObject->paymentId,
            'amount' => $createdPaymentObject->amount,
        ];

        if ($createdPaymentObject->paymentUrl !== null) {
            $response['url'] = $createdPaymentObject->paymentUrl;
        }

        if ($createdPaymentObject->clientSecret !== null) {
            $payment->client_secret = $createdPaymentObject->clientSecret;
            $response['client_secret'] = $createdPaymentObject->clientSecret;
        }

        $payment->method_id = $createdPaymentObject->paymentId;
        $payment->status = $createdPaymentObject->status;

        DB::transaction(function () use ($payment): void {
            $payment->save();
        });

        return $response;
    }
}
