<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationFailed;

class NotificationFailedListener
{
    public function __construct()
    {
    }

    public function handle(NotificationFailed $event): void
    {
    }
}
