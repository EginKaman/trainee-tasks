<?php

declare(strict_types=1);

namespace App\Services\Payment\Objects;

class Refund
{
    public function __construct(
        public string $paymentId,
        public float $amount
    ) {
    }
}
