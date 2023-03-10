<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\{CancelSubscribeRequest, SubscribeRequest};
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\{Carbon, Str};
use Srmklive\PayPal\Facades\PayPal;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class SubscribeController extends Controller
{
    public function subscribe(SubscribeRequest $request): JsonResponse
    {
        $user = auth('api')->user();
        $subscription = Subscription::find($request->validated('subscription_id'));

        if ($subscription->whereHas('users', function ($query) use ($user): void {
            $query->where('users.id', $user->id);
        })->exists()) {
            return response()->json([
                'message' => __('You already have the subscription'),
            ], 409);
        }

        if ($request->type_payment === 'paypal') {
            $provider = Paypal::setProvider();
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();
            $provider->setRequestHeader('Authorization', 'Bearer ' . $token['access_token']);
            $provider->setRequestHeader('PayPal-Request-Id', Str::uuid()->toString());

            $plan = $provider->createSubscription([
                'plan_id' => $subscription->paypal_id,
                'quantity' => 1,
                'application_context' => [
                    'return_url' => url('api/v1/payments/paypal'),
                    'cancel_url' => url('paypal/cancel'),
                ],
            ]);

            $redirectUrl = '';
            foreach ($plan['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    $redirectUrl = $link['href'];
                }
            }

            $user->subscriptions()->attach($subscription, [
                'method' => $request->type_payment,
                'method_id' => $plan['id'],
                'status' => $plan['status'],
                'started_at' => now(),
                'expired_at' => now()->addMonth(),
            ]);

            return response()->json([
                'url' => $redirectUrl,
            ]);
        }
        $stripe = new StripeClient(config('services.stripe.api_secret'));

        if ($user->stripe_id === null) {
            $customer = $stripe->customers->create([
                'email' => $user->email,
                'name' => $user->name,
                'phone' => $user->phone,
            ]);

            $user->stripe_id = $customer->id;
            $user->save();
        }

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

    public function cancel(CancelSubscribeRequest $request): JsonResponse
    {
        $user = auth('api')->user();

        $subscription = $user->subscriptions()->wherePivot('status', '!=', 'canceled')->find(
            $request->validated('subscription_id')
        );

        if (!$subscription) {
            return response()->json([
                'message' => __("Your didn't subscribe to this subscription"),
            ], 404);
        }

        if ($subscription->pivot->method === 'paypal') {
            $provider = Paypal::setProvider();
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();
            $provider->setRequestHeader('Authorization', 'Bearer ' . $token['access_token']);
            $provider->setRequestHeader('PayPal-Request-Id', Str::uuid()->toString());

            $provider->cancelSubscription($subscription->pivot->method_id, 'Canceled by user');

            $subscription->pivot->status = 'canceled';
            $subscription->pivot->canceled_at = now();

            $subscription->pivot->save();
        }

        if ($subscription->pivot->method === 'stripe') {
            $stripe = new StripeClient(config('services.stripe.api_secret'));

            try {
                $response = $stripe->subscriptions->cancel($subscription->pivot->method_id);
            } catch (ApiErrorException $exception) {
                return response()->json([
                    'status' => __('Failure'),
                    'message' => $exception->getMessage(),
                ], $exception->getHttpStatus());
            }

            $user->subscriptions()->syncWithPivotValues($subscription, [
                'method_id' => $response->id,
                'status' => $response->status,
                'canceled_at' => Carbon::createFromTimestamp($response->canceled_at),
                'started_at' => Carbon::createFromTimestamp($response->start_date),
                'expired_at' => Carbon::createFromTimestamp($response->current_period_end),
            ]);
        }

        return response()->json([
            'status' => __('Success'),
            'message' => __('Your subscription was canceled. Updating subscription information will be soon.'),
        ]);
    }
}
