<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequest;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class SubscribeController extends Controller
{
    public function subscribe(SubscribeRequest $request): JsonResponse
    {
        $stripe = new StripeClient(config('services.stripe.api_secret'));

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
        $subscription = Subscription::find($request->validated('subscription_id'));
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

    public function cancel(SubscribeRequest $request): JsonResponse
    {
        $stripe = new StripeClient(config('services.stripe.api_secret'));

        $user = auth('api')->user();

        $subscription = $user->subscriptions()->wherePivot('status', 'active')->find(
            $request->validated('subscription_id')
        );

        if (!$subscription) {
            return response()->json([
                'status' => __('Failure'),
                'message' => __('Your subscription has already canceled.'),
            ]);
        }

        try {
            $response = $stripe->subscriptions->cancel($subscription->pivot->pay_id);
        } catch (ApiErrorException $exception) {
            return response()->json([
                'status' => __('Failure'),
                'message' => $exception->getMessage(),
            ], $exception->getHttpStatus());
        }

        $user->subscriptions()->syncWithPivotValues($subscription, [
            'pay_id' => $response->id,
            'status' => $response->status,
            'canceled_at' => Carbon::createFromTimestamp($response->canceled_at),
            'started_at' => Carbon::createFromTimestamp($response->start_date),
            'expired_at' => Carbon::createFromTimestamp($response->current_period_end),
        ]);

        return response()->json([
            'status' => __('Success'),
            'message' => __('Your subscription was canceled. Updating subscription information will be soon.'),
        ]);
    }
}
