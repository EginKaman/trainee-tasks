<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\DataTransferObjects\{CreatedPaymentObject, Refund};
use App\DataTransferObjects\{EventObject, NewPaymentObject};
use Illuminate\Http\Request;

interface PaymentClient
{
    public function payment(NewPaymentObject $paymentObject): CreatedPaymentObject;

    public function refund(Refund $refund): void;

    public function createEvent(Request $request): EventObject;
}
