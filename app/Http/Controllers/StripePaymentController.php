<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\{Subscription, User};
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Stripe\StripeClient;

class StripePaymentController extends Controller
{
    public function success(Request $request): View
    {
        $sessionId = $request->get('session_id');

        $stripe = new StripeClient(config('services.stripe.api_secret'));

        $checkout_session = $stripe->checkout->sessions->retrieve($sessionId);

        $user = User::where('stripe_id', $checkout_session->customer)->first();

        $subscription = $stripe->subscriptions->retrieve($checkout_session->subscription);

        /**
         * @phpstan-ignore-next-line
         */
        $planId = $subscription->plan->id;
        $user->subscriptions()->syncWithPivotValues(
            Subscription::where('stripe_id', $planId)->first(),
            [
                'method' => 'stripe',
                'method_id' => $subscription->id,
                'status' => $subscription->status,
                'started_at' => Carbon::createFromTimestamp($subscription->start_date),
                'expired_at' => Carbon::createFromTimestamp($subscription->current_period_end),
            ]
        );

        return view('payments.success');
    }
}
