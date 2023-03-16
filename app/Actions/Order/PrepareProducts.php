<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\{Order, OrderProduct, Product};
use Illuminate\Support\{Arr, Collection};

class PrepareProducts
{
    public function prepare(Order $order, array $data): Collection
    {
        $data = Arr::keyBy($data, 'id');

        $products = Product::query()->whereIn(
            'id',
            Arr::map($data['products'], fn (array $value) => $value['id'])
        )->get();

        $orderProducts = collect();
        foreach ($products as $product) {
            $orderProduct = new OrderProduct([
                'quantity' => $data[$product->id]['quantity'],
                'image' => $product->image,
                'price' => $product->price,
            ]);
            $orderProduct->fill($product->getTranslationsArray());
            $orderProduct->order()->associate($order);
            $orderProducts->push($orderProduct);
        }

        return $orderProducts;
    }
}
