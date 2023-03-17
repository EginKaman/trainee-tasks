<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\DataTransferObjects\{CreatedPaymentObject, NewPaymentObject};
use App\Exceptions\UnknownPaymentMethodException;
use App\Models\{Card, Order, Payment, User};
use App\Services\Payment\Payment as PaymentClient;
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

        $payment = $this->preparePayment($order, $user, $request['type_payment']);

        $paymentClient = new PaymentClient($payment->method);

        $newPaymentObject = $this->prepareNewPaymentObject(
            $payment,
            $user,
            $request['save_card'],
            $this->getCard($request)
        );

        $createdPaymentObject = $paymentClient->payment($newPaymentObject);

        $payment->client_secret = $createdPaymentObject->clientSecret;
        $payment->method_id = $createdPaymentObject->paymentId;
        $payment->status = $createdPaymentObject->status;

        $payment->save();

        return $this->prepareResponse($createdPaymentObject);
    }

    private function preparePayment(Order $order, User $user, string $typePayment): Payment
    {
        $payment = new Payment([
            'method' => $typePayment,
            'currency' => 'USD',
            'amount' => $order->amount,
        ]);

        $payment->user()->associate($user);
        $payment->order()->associate($order);

        return $payment;
    }

    private function getCard(array $request): ?Card
    {
        if (isset($request['card_id'])) {
            return Card::find($request['card_id']);
        }

        return null;
    }

    private function prepareNewPaymentObject(
        Payment $payment,
        User $user,
        bool $saveCard,
        ?Card $card = null
    ): NewPaymentObject {
        return new NewPaymentObject(
            amount: $payment->amount,
            user: $user,
            saveCard: $saveCard,
            card: $card ?? null
        );
    }

    private function prepareResponse(CreatedPaymentObject $createdPaymentObject): array
    {
        $response = [
            'type_payment' => $createdPaymentObject->typePayment,
            'payment_id' => $createdPaymentObject->paymentId,
            'amount' => $createdPaymentObject->amount,
        ];

        if ($createdPaymentObject->paymentUrl !== null) {
            $response['url'] = $createdPaymentObject->paymentUrl;
        }

        if ($createdPaymentObject->clientSecret !== null) {
            $response['client_secret'] = $createdPaymentObject->clientSecret;
        }

        return $response;
    }
}
