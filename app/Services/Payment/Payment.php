<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Exceptions\UnknownPaymentMethodException;
use App\Services\Payment\Objects\{CreatedPaymentObject, NewPaymentObject};
use App\Services\Payment\Paypal\Client as PaypalClient;
use App\Services\Payment\Stripe\Client as StripeClient;

class Payment
{
    private PaymentClient $client;
    private string $paymentMethod;

    /**
     * @throws UnknownPaymentMethodException
     */
    public function __construct(string $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->selectPaymentClient();
    }

    public function payment(NewPaymentObject $paymentObject): CreatedPaymentObject
    {
        return $this->client->payment($paymentObject);
    }

    public function refund(): void
    {
    }

    /**
     * @throws UnknownPaymentMethodException
     */
    private function selectPaymentClient(): void
    {
        $this->client = match ($this->paymentMethod) {
            'paypal' => new PaypalClient(),
            'stripe' => new StripeClient(),
            default => throw new UnknownPaymentMethodException(),
        };
    }
}
