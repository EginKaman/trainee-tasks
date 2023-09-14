<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enum\OrderStatus;
use App\Models\Order;
use App\Notifications\{CheckoutNotification, RefundNotification};

class OrderObserver
{
    public function created(Order $order): void
    {
        $order->user->notify(new CheckoutNotification($order));
    }

    public function updated(Order $order): void
    {
        if ($order->wasChanged('status') && $order->status === OrderStatus::Refunded) {
            $order->user->notify(new RefundNotification($order));
        }
    }

    public function deleted(Order $order): void
    {
    }

    public function restored(Order $order): void
    {
    }

    public function forceDeleted(Order $order): void
    {
    }
}
