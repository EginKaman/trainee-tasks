<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Payment\NewPayment;
use App\Http\Requests\StorePaymentRequest;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function store(StorePaymentRequest $request, NewPayment $payment): void
    {
        $payment->create($request);
    }

    public function refund(Request $request): void
    {
    }
}
