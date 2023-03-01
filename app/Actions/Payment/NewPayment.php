<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Http\Requests\StorePaymentRequest;
use App\Models\{Order, Payment};

class NewPayment
{
    public function create(StorePaymentRequest $request): void
    {
        $order = Order::find($request->validated('order_id'));

        new Payment([
            'method' => 'stripe',
            'currency' => 'usd',
            'amount' => $order->amount,
        ]);
    }
}
