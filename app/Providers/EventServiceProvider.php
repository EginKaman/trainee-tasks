<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\{BuildingMenuListener, SuccessfulSentMessage};
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSent;
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
    ];

    /**
     * The model observers for your application.
     *
     * @var array<string, array<int, object|string>|object|string>
     */
    protected $observers = [
        User::class => [UserObserver::class],
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
