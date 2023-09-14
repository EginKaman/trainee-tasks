<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\{TwilioChannel, TwilioSmsMessage};

class CheckoutNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order
    )
    {
    }

    public function via(mixed $notifiable): array
    {
        return [TwilioChannel::class];
    }

    public function toTwilio(mixed $notifiable): TwilioSmsMessage
    {
        return (new TwilioSmsMessage())
            ->content("Your order #{$this->order->id} is ordered. Thank you!");
    }

    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
