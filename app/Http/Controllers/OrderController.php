<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Order\NewOrder;
use App\Exceptions\OrderCreateException;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\{OrderCollection, OrderResource};
use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(): OrderCollection
    {
        return new OrderCollection(OrderRepository::getUserOrders(auth('api')->user()));
    }

    public function store(StoreOrderRequest $request, NewOrder $newOrder): OrderResource|JsonResponse
    {
        try {
            return new OrderResource($newOrder->create($request->user(), $request->validated()));
        } catch (OrderCreateException $exception) {
            return response()->json([
                'message' => $exception,
            ], 500);
        }
    }
}
