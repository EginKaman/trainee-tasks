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
            if(method_exists($event->notification, 'toTurboSMS')) {
                $channel = 'turbosms';
                $text = $event->notification->toTurboSMS($event->notifiable)->body;
            } else {
                $channel = 'twilio';
                $text = $event->notification->toTwilio($event->notifiable)->content;
            }
            $smsMessage = new SmsMessage([
                'id' => $event->notification->id,
                'phone' => $event->notifiable->phone,
                'text' => $text,
                'channel' => $channel,
                'status' => 'pending',
            ]);
            $smsMessage->save();
        }
    }
}
