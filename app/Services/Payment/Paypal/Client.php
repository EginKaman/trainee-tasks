<?php

declare(strict_types=1);

namespace App\Services\Payment\Paypal;

use App\DataTransferObjects\{CreatedPaymentObject, Refund};
use App\DataTransferObjects\{CreatedSubscriptionObject, EventObject, NewPaymentObject, NewSubscribeObject};
use App\Exceptions\PaypalSignatureVerificationException;
use App\Services\Payment\PaymentClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Srmklive\PayPal\Facades\PayPal;
use Throwable;

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

    public function refund(Refund $refund): void
    {
        $this->client->refundCapturedPayment($refund->paymentId, '', $refund->amount, 'Refunded by client.');
    }

    /**
     * @throws Throwable
     */
    public function createEvent(Request $request): EventObject
    {
        $this->validateSignature($request);

        return new EventObject(
            event: $request->event_type,
            orderId: $request->resource['supplementary_data']['related_ids']['order_id'] ?? null,
            status: $request->resource['status']
        );
    }

    /**
     * @throws Throwable
     */
    public function subscribe(NewSubscribeObject $newSubscriptionObject): CreatedSubscriptionObject
    {
        $plan = $this->client->createSubscription([
            'plan_id' => $newSubscriptionObject->planId,
            'quantity' => 1,
            'application_context' => [
                'return_url' => url('payments/paypal/success'),
                'cancel_url' => url('payments/paypal/cancel'),
            ],
        ]);

        $redirectUrl = '';
        foreach ($plan['links'] as $link) {
            if ($link['rel'] === 'approve') {
                $redirectUrl = $link['href'];
            }
        }

        return new CreatedSubscriptionObject($redirectUrl, $plan['id']);
    }

    /**
     * @throws Throwable
     */
    public function cancelSubscribe(string $subscribeId): void
    {
        $this->client->cancelSubscription($subscribeId, 'Canceled by user');
    }

    /**
     * @throws Throwable
     */
    private function validateSignature(Request $request): void
    {
        $verify = $this->client->verifyWebHook([
            'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
            'cert_url' => $request->header('PAYPAL-CERT-URL'),
            'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
            'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
            'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
            'webhook_event' => $request->all(),
            'webhook_id' => config('paypal.' . config('paypal.mode') . '.webhook_id'),
        ]);

        if ($verify['verification_status'] === 'FAILURE') {
            throw new PaypalSignatureVerificationException();
        }
    }
}
