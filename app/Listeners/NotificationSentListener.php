<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationSent;

class NotificationSentListener
{
    public function __construct()
    {
    }

    public function handle(NotificationSent $event): void
    {
    }
}
