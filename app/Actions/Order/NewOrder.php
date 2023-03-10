<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Enum\OrderStatus;
use App\Models\{Order, OrderProduct, Product, User};

class NewOrder
{
    public function create(User $user, array $data): Order
    {
        $order = new Order([
            'status' => OrderStatus::Created,
        ]);
        $order->user()->associate($user);
        $order->save();
        foreach ($data['products'] as $product) {
            $productModel = Product::query()->find($product['id']);
            $orderProduct = new OrderProduct([
                'quantity' => $product['quantity'],
                'image' => $productModel->image,
                'price' => $productModel->price,
            ]);
            $orderProduct->fill($productModel->getTranslationsArray());
            $orderProduct->order()->associate($order);

            $orderProduct->save();
        }

        $order->amount = round($order->products()->sum('price'), 2);
        $order->save();

        return $order;
    }
}
