<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

class CreatedPaymentObject
{
    public function __construct(
        public string $typePayment,
        public string $paymentId,
        public string $status,
        public ?string $paymentUrl = null,
        public ?int $amount = null,
        public ?string $clientSecret = null
    ) {
    }
}
