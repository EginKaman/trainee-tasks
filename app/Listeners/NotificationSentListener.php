<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enum\NotificationStatus;
use App\Models\SmsMessage;
use Illuminate\Notifications\Events\NotificationSent;

class NotificationSentListener
{
    public function __construct()
    {
    }

    public function handle(NotificationSent $event): void
    {
        SmsMessage::find($event->notification->id)->update([
            'status' => NotificationStatus::Success,
        ]);
    }
}
