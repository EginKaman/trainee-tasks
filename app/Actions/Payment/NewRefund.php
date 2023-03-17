<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\DataTransferObjects\Refund;
use App\Enum\OrderStatus;
use App\Exceptions\{OrderNotPayedException, OrderRefundedException};
use App\Models\Order;
use App\Services\Payment\Payment as PaymentClient;
use Illuminate\Support\Facades\DB;

class NewRefund
{
    public function refund(int $orderId): void
    {
        $order = Order::find($orderId);

        if ($order->status === OrderStatus::Refunded->value) {
            throw new OrderRefundedException(__('Order has already refunded'));
        }

        if ($order->payments()->where('status', '!=', OrderStatus::Refunded)->count() === 0) {
            throw new OrderNotPayedException(__("Order doesn't have payment"));
        }

        $payment = $order->payments()->where('status', '!=', OrderStatus::Refunded)->latest()->first();

        $paymentClient = new PaymentClient($payment->method);

        $paymentClient->refund(new Refund($payment->method_id, $payment->amount));

        DB::transaction(function () use ($order, $payment): void {
            $order->status = OrderStatus::Refunded;
            $order->save();

            $payment->status = OrderStatus::Refunded;
            $payment->save();
        });
    }
}
