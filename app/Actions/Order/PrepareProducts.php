<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\{Order, OrderProduct, Product};
use Illuminate\Support\{Arr, Collection, Facades\Log};

class PrepareProducts
{
    public function prepare(array $data): Collection
    {
        $data = Arr::keyBy($data, 'id');

        $products = Product::query()->whereIn('id', Arr::map($data, fn (array $value) => $value['id']))->get();

        $orderProducts = collect();
        foreach ($products as $product) {
            $orderProduct = new OrderProduct([
                'quantity' => $data[$product->id]['quantity'],
                'image' => $product->image,
                'price' => $product->price,
            ]);
            $orderProduct->fill($product->getTranslationsArray());
            $orderProducts->push($orderProduct);
        }

        return $orderProducts;
    }
}
