<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Order\NewOrder;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\{OrderCollection, OrderResource};
use App\Repositories\OrderRepository;
use Illuminate\Http\{JsonResponse, Request};

class OrderController extends Controller
{
    public function index(Request $request): OrderCollection
    {
        return new OrderCollection(OrderRepository::getUserOrders($request->user()));
    }

    public function store(StoreOrderRequest $request, NewOrder $newOrder): OrderResource|JsonResponse
    {
        return new OrderResource($newOrder->create($request->user(), $request->validated()));
    }
}
