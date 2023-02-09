<?php

declare(strict_types=1);

namespace App\Http\Controllers\Images;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function __invoke(Request $request): void
    {
    }
}
