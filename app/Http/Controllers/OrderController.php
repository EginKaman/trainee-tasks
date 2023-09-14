<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Order\{NewOrder, RefundOrder};
use App\Exceptions\OrderRefundedException;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\{OrderCollection, OrderResource};
use App\Models\Order;
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

    public function refund(Order $order, RefundOrder $refundOrder): JsonResponse
    {
        try {
            $refundOrder->refund($order);
        } catch (OrderRefundedException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }

        return response()->json([
            'message' => __('Order has been refunded'),
        ]);
    }
}
