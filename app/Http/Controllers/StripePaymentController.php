<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\{Subscription, User};
use Illuminate\Contracts\View\View;

class StripePaymentController extends Controller
{
    public function success(): View
    {
        return view('payments.success');
    }
}
