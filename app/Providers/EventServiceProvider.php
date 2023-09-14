<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\{BuildingMenuListener,NotificationFailedListener,NotificationSendingListener,NotificationSentListener,SuccessfulSentMessage};
use App\Models\{Order, User};
use App\Observers\{OrderObserver, UserObserver};
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Notifications\Events\{NotificationFailed, NotificationSending, NotificationSent};
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],
        MessageSent::class => [SuccessfulSentMessage::class],
        BuildingMenu::class => [BuildingMenuListener::class],
        NotificationSending::class => [NotificationSendingListener::class],
        NotificationSent::class => [NotificationSentListener::class],
        NotificationFailed::class => [NotificationFailedListener::class],
    ];

    /**
     * The model observers for your application.
     *
     * @var array<string, array<int, object|string>|object|string>
     */
    protected $observers = [
        User::class => [UserObserver::class],
        Order::class => [OrderObserver::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
