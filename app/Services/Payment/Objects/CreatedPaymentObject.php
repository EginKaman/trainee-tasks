<?php

declare(strict_types=1);

namespace App\Services\Payment\Objects;

class CreatedPaymentObject
{
    public string $typePayment;
    public string $paymentId;
    public ?string $paymentUrl;
    public ?int $amount;
    public ?string $clientSecret;
    public string $status;

    public function __construct(
        string $typePayment,
        string $paymentId,
        string $status,
        ?string $paymentUrl = null,
        ?int $amount = null,
        ?string $clientSecret = null
    ) {
        $this->typePayment = $typePayment;
        $this->paymentId = $paymentId;
        $this->paymentUrl = $paymentUrl;
        $this->status = $status;
        $this->amount = $amount;
        $this->clientSecret = $clientSecret;
    }
}
