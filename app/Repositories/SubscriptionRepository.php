<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionRepository
{
    public static function getSubscriptions(): Collection
    {
        return Subscription::withTranslation()->get();
    }
}
