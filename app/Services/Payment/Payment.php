<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Exceptions\UnknownPaymentMethodException;
use App\Services\Payment\Objects\{CreatedPaymentObject, NewPaymentObject, Refund};
use App\Services\Payment\Paypal\Client as PaypalClient;
use App\Services\Payment\Stripe\Client as StripeClient;

class Payment
{
    protected PaymentClient $client;
    protected string $paymentMethod;

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

    public function refund(Refund $refund): void
    {
        $this->client->refund($refund);
    }

    /**
     * @throws UnknownPaymentMethodException
     */
    protected function selectPaymentClient(): void
    {
        $this->client = match ($this->paymentMethod) {
            'paypal' => new PaypalClient(),
            'stripe' => new StripeClient(),
            default => throw new UnknownPaymentMethodException(),
        };
    }
}
