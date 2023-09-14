<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\SmsMessage;
use App\Notifications\{CheckoutNotification, RefundNotification};
use Illuminate\Notifications\Events\NotificationSending;

class NotificationSendingListener
{
    public function __construct()
    {
    }

    public function handle(NotificationSending $event): void
    {
        if ($event->notification instanceof CheckoutNotification || $event->notification instanceof RefundNotification) {
            $smsMessage = new SmsMessage([
                'phone' => $event->notifiable->phone,
                'text' => $event->notification->toVonage($event->notifiable)->content,
                'is_sent' => false,
            ]);
        }
    }
}
