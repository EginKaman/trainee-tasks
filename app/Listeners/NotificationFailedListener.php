<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enum\NotificationStatus;
use App\Models\SmsMessage;
use Illuminate\Notifications\Events\NotificationFailed;

class NotificationFailedListener
{
    public function __construct()
    {
    }

    public function handle(NotificationFailed $event): void
    {
        SmsMessage::find($event->notification->id)->update([
            'status' => NotificationStatus::Failed,
        ]);
    }
}
