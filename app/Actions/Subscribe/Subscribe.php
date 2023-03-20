<?php

declare(strict_types=1);

namespace App\Actions\Subscribe;

use App\DataTransferObjects\NewSubscribeObject;
use App\Enum\SubscriptionStatus;
use App\Exceptions\AlreadySubscribedException;
use App\Models\{Subscription, User};
use App\Services\Payment\Payment;

class Subscribe
{
    public function subscribe(User $user, int $subscriptionId, string $typePayment): string
    {
        $subscription = Subscription::find($subscriptionId);

        if ($subscription->users()
            ->wherePivot('user_id', $user->id)
            ->wherePivot('status', '!=', SubscriptionStatus::Canceled->value)->exists()) {
            throw new AlreadySubscribedException();
        }

        $paymentClient = new Payment($typePayment);

        $createdSubscribe = $paymentClient->subscribe(
            new NewSubscribeObject($subscription->{$typePayment . '_id'}, $user)
        );

        $user->subscriptions()->syncWithPivotValues($subscription, [
            'method' => $typePayment,
            'method_id' => $createdSubscribe->subscriptionId,
            'status' => SubscriptionStatus::Pending->value,
        ]);

        return $createdSubscribe->url;
    }
}
