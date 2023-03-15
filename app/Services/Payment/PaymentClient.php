<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Services\Payment\Objects\{CreatedPaymentObject, NewPaymentObject, Refund};

interface PaymentClient
{
    public function payment(NewPaymentObject $paymentObject): CreatedPaymentObject;

    public function refund(Refund $refund): void;
}
