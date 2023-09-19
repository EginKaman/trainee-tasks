<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\VonageSmsChannel;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use NotificationChannels\TurboSMS\TurboSMSMessage;

class RefundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order
    )
    {
    }

    public function via(mixed $notifiable): array
    {
//        return [VonageSmsChannel::class];
        return ['turbosms'];
    }

    public function toVonage(mixed $notifiable): VonageMessage
    {
        return (new VonageMessage())
            ->unicode()
            ->content("Your order #{$this->order->id} is refunded. Thank you!");
    }

    public function toTurboSMS(mixed $notifiable): TurboSmsMessage
    {
        return new TurboSmsMessage(__('Your order #:order is refunded. Thank you!', [
            'order' => $this->order->id,
        ], Arr::random(['ar', 'en', 'ru'])));
    }

    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
