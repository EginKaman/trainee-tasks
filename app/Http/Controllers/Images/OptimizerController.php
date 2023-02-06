<?php

declare(strict_types=1);

namespace App\Http\Controllers\Images;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OptimizerController extends Controller
{
    public function index()
    {
        return view('images.optimizer');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('optimizer');
    }
}
