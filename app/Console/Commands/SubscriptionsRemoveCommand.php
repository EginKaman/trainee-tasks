<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\{Subscription, SubscriptionTranslation};
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\{Schema, Storage};

class SubscriptionsRemoveCommand extends Command
{
    protected $signature = 'subscriptions:remove';

    protected $description = 'Removed all subscriptions from database.';

    public function handle(): void
    {
        Subscription::whereNotNull('image')->chunk(20, function (Collection $subscriptions): void {
            Storage::delete($subscriptions->map(fn (Subscription $subscription) => $subscription->image)->toArray());
        });

        Schema::disableForeignKeyConstraints();

        SubscriptionTranslation::truncate();
        Subscription::truncate();

        Schema::enableForeignKeyConstraints();
    }
}
