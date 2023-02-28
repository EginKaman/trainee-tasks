<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\{Order, OrderProduct, Product};

class NewOrder
{
    public function create(array $data): Order
    {
        $order = new Order();
        $order->user()->associate(auth('api')->user());
        $order->save();
        foreach ($data['products'] as $product) {
            $productModel = Product::query()->withTranslation()->find($product['id']);
            $orderProduct = new OrderProduct([
                'quantity' => $product['quantity'],
                'image' => $productModel->image,
                'price' => $productModel->price,
            ]);
            $orderProduct->fill($productModel->getTranslationsArray());
            $orderProduct->order()->associate($order);

            $orderProduct->save();
        }

        $order->total_price = round($order->products()->sum('price'), 2);
        $order->save();

        return $order;
    }
}
