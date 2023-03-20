<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\DataTransferObjects\{CreatedPaymentObject, Refund};
use App\DataTransferObjects\{CreatedSubscriptionObject, EventObject, NewPaymentObject, NewSubscribeObject};
use Illuminate\Http\Request;

interface PaymentClient
{
    public function payment(NewPaymentObject $paymentObject): CreatedPaymentObject;

    public function refund(Refund $refund): void;

    public function createEvent(Request $request): EventObject;

    public function subscribe(NewSubscribeObject $newSubscriptionObject): CreatedSubscriptionObject;

    public function cancelSubscribe(string $subscribeId): void;
}
