<?php

declare(strict_types=1);

namespace App\Services\Payment\Stripe;

use App\DataTransferObjects\{CreatedPaymentObject, Refund};
use App\DataTransferObjects\{CreatedSubscriptionObject, EventObject, NewPaymentObject, NewSubscribeObject};
use App\Models\User;
use App\Services\Payment\PaymentClient;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\{Exception\ApiErrorException, StripeClient, Webhook as StripeWebhook};

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

    /**
     * @throws SignatureVerificationException
     */
    public function createEvent(Request $request): EventObject
    {
        $event = StripeWebhook::constructEvent(
            $request->getContent(),
            $request->server('HTTP_STRIPE_SIGNATURE'),
            config
            (
                'services.stripe.webhook_secret'
            )
        );
        /** @phpstan-ignore-next-line */
        $dataObject = $event->data->object;

        return new EventObject(
            event: $event->type,
            orderId: $dataObject->id,
            status: $dataObject->status,
            clientSecret: $dataObject->client_secret,
            customer: $dataObject->customer,
            plan: $dataObject->plan ?? null,
            startDate: $dataObject->start_date,
            endDate: $dataObject->current_period_end
        );
    }

    public function subscribe(NewSubscribeObject $newSubscriptionObject): CreatedSubscriptionObject
    {
        if ($newSubscriptionObject->user->stripe_id === null) {
            $customer = $this->client->customers->create([
                'email' => $newSubscriptionObject->user->email,
                'name' => $newSubscriptionObject->user->name,
                'phone' => $newSubscriptionObject->user->phone,
            ]);

            $newSubscriptionObject->user->stripe_id = $customer->id;
            $newSubscriptionObject->user->save();
        }

        $product = $this->client->prices->retrieve($newSubscriptionObject->planId);

        $checkout_session = $this->client->checkout->sessions->create([
            'line_items' => [
                [
                    'price' => $product->id,
                    'quantity' => 1,
                ],
            ],
            'customer' => $newSubscriptionObject->user->stripe_id,
            'mode' => 'subscription',
            'success_url' => url('payments/stripe/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('payments/stripe/cancel'),
        ]);

        return new CreatedSubscriptionObject($checkout_session->url);
    }

    /**
     * @throws ApiErrorException
     */
    public function cancelSubscribe(string $subscribeId): void
    {
        $stripe = new StripeClient(config('services.stripe.api_secret'));

        $stripe->subscriptions->cancel($subscribeId);
    }
}
