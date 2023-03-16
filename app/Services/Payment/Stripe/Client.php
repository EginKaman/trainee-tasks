<?php

declare(strict_types=1);

namespace App\Services\Payment\Stripe;

use App\Models\User;
use App\Services\Payment\Objects\{CreatedPaymentObject, NewPaymentObject, Refund};
use App\Services\Payment\PaymentClient;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class Client implements PaymentClient
{
    private StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(config('services.stripe.api_secret'));
    }

    public function payment(NewPaymentObject $paymentObject): CreatedPaymentObject
    {
        $user = $paymentObject->user;
        if ($user->stripe_id === null) {
            $user = $this->createCustomer($user);
        }
        $paymentIntentRequest = [
            'amount' => $paymentObject->amount * 100,
            'currency' => 'usd',
        ];
        if ($paymentObject->card !== null) {
            $card = $paymentObject->card;
            $paymentIntentRequest = array_merge($paymentIntentRequest, [
                'customer' => $user->stripe_id,
                'payment_method' => $card->fingerprint,
                'off_session' => true,
                'confirm' => true,
            ]);
        } elseif ($paymentObject->saveCard === true) {
            $paymentIntentRequest = array_merge($paymentIntentRequest, [
                'customer' => $user->stripe_id,
                'setup_future_usage' => 'off_session',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
        } else {
            $paymentIntentRequest = array_merge($paymentIntentRequest, [
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
        }
        $paymentIntent = $this->client->paymentIntents->create($paymentIntentRequest);

        return new CreatedPaymentObject(
            'stripe',
            $paymentIntent->id,
            $paymentIntent->status,
            null,
            $paymentObject->amount,
            $paymentIntent->client_secret
        );
    }

    public function refund(Refund $refund): void
    {
        $paymentIntent = $this->client->paymentIntents->retrieve($refund->paymentId);
        $this->client->refunds->create([
            'charge' => $paymentIntent->latest_charge,
        ]);
    }

    public function createCustomer(User $user): User
    {
        $customer = $this->client->customers->create([
            'email' => $user->email,
            'name' => $user->name,
            'phone' => $user->phone,
        ]);

        $user->stripe_id = $customer->id;
        $user->save();

        return $user;
    }

    public function validateSignature(Request $request): void
    {
    }
}
