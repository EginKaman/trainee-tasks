<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Enum\OrderStatus;
use App\Exceptions\OrderRefundedException;
use App\Models\Order;

class RefundOrder
{
    public function refund(Order $order): void
    {
        if ($order->status === OrderStatus::Refunded) {
            throw new OrderRefundedException(__('Order has already refunded'));
        }
        $order->status = OrderStatus::Refunded;
        $order->save();
    }
}
