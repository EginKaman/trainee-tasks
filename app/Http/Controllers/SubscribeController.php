<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\{Subscription, User};
use Illuminate\Http\JsonResponse;

class SubscribeController extends Controller
{
    public function subscribe(): JsonResponse
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.api_secret'));

        auth('api')->login(User::find(2));
        $user = auth('api')->user();

        if ($user->stripe_id === null) {
            $customer = $stripe->customers->create([
                'email' => $user->email,
                'name' => $user->name,
                'phone' => $user->phone,
            ]);

            $user->stripe_id = $customer->id;
            $user->save();
        }
        $subscription = Subscription::find(1);
        $product = $stripe->prices->retrieve($subscription->stripe_id);
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price' => $product->id,
                    'quantity' => 1,
                ],
            ],
            'customer' => $user->stripe_id,
            'mode' => 'subscription',
            'success_url' => url('payments/stripe/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => route('payments.stripe.cancel'),
        ]);

        return response()->json([
            'url' => $checkout_session->url,
        ]);
    }

    public function cancel(): void
    {
    }
}
