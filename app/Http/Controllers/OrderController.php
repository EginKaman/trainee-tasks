<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Order\NewOrder;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\{OrderCollection, OrderResource};

class OrderController extends Controller
{
    public function index(): OrderCollection
    {
        return new OrderCollection(auth('api')->user()->orders()->with(['products', 'products.translation'])->get());
    }

    public function store(StoreOrderRequest $request, NewOrder $newOrder): OrderResource
    {
        return new OrderResource($newOrder->create(auth('api')->user(), $request->validated()));
    }
}
