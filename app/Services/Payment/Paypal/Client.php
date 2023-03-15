<?php

declare(strict_types=1);

namespace App\Services\Payment\Paypal;

use App\Services\Payment\Objects\{CreatedPaymentObject, NewPaymentObject};
use App\Services\Payment\PaymentClient;
use Illuminate\Support\Str;
use Srmklive\PayPal\Facades\PayPal;

class Client implements PaymentClient
{
    private \Srmklive\PayPal\Services\PayPal $client;

    public function __construct()
    {
        $provider = Paypal::setProvider();
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setRequestHeader('Authorization', 'Bearer ' . $token['access_token']);
        $provider->setRequestHeader('PayPal-Request-Id', Str::uuid()->toString());

        $this->client = $provider;
    }

    public function client(): void
    {
    }

    public function payment(NewPaymentObject $paymentObject): CreatedPaymentObject
    {
        $order = $this->client->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $paymentObject->amount,
                    ],
                ],
            ],
            'application_context' => [
                'return_url' => url('api/v1/payments/paypal'),
                'cancel_url' => url('paypal/cancel'),
            ],
        ]);

        $redirectUrl = '';
        foreach ($order['links'] as $link) {
            if ($link['rel'] === 'approve') {
                $redirectUrl = $link['href'];
            }
        }

        return new CreatedPaymentObject('paypal', $order['id'], $order['status'], $redirectUrl, $paymentObject->amount);
    }

    public function refund(): void
    {
    }
}
