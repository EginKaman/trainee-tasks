<?php

declare(strict_types=1);

namespace App\Actions\Subscribe;

use App\Enum\SubscriptionStatus;
use App\Exceptions\NotSubscribedException;
use App\Models\User;
use App\Services\Payment\Payment;

class CancelSubscribe
{
    public function cancel(User $user, int $subscriptionId): void
    {
        $subscription = $user->subscriptions()->wherePivotNotIn(
            'status',
            [SubscriptionStatus::Pending->value, SubscriptionStatus::Canceled->value]
        )->find($subscriptionId);

        if (!$subscription) {
            throw new NotSubscribedException();
        }
        $paymentClient = new Payment($subscription->pivot->method);

        $paymentClient->cancelSubscribe($subscription->pivot->method_id);

        $user->subscriptions()->syncWithPivotValues($subscription, [
            'method' => $subscription->pivot->method,
            'method_id' => $subscription->pivot->method_id,
            'status' => SubscriptionStatus::Canceled->value,
            'canceled_at' => now(),
        ]);
    }
}
