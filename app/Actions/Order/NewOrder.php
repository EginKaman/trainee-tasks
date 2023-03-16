<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Enum\OrderStatus;
use App\Exceptions\OrderCreateException;
use App\Models\{Order, OrderProduct, User};
use Exception;
use Illuminate\Support\Facades\DB;

class NewOrder
{
    public function __construct(
        private PrepareProducts $prepareProducts
    ) {
    }

    /**
     * @throws OrderCreateException
     */
    public function create(User $user, array $data): Order
    {
        $order = new Order([
            'status' => OrderStatus::Created,
        ]);
        $order->user()->associate($user);

        $orderProducts = $this->prepareProducts->prepare($order, $data['products']);

        $order->amount = round(
            $orderProducts->sum(fn (OrderProduct $orderProduct) => $orderProduct->quantity * $orderProduct->price),
            2
        );

        DB::beginTransaction();

        try {
            $order->save();

            $order->products()->saveMany($orderProducts);
        } catch (Exception $e) {
            DB::rollBack();

            throw new OrderCreateException();
        }

        DB::commit();

        return $order;
    }
}
