<?php

declare(strict_types=1);

namespace App\Http\Controllers\Images;

use App\Http\Controllers\Controller;
use App\Http\Requests\Images\StoreOptimizerRequest;
use App\Services\Images\Anotate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OptimizerController extends Controller
{
    public function index()
    {
        return view('images.optimizer');
    }

    public function store(StoreOptimizerRequest $request, Anotate $anotate): \Illuminate\Http\RedirectResponse
    {
        $anotate->handle(Storage::path($request->image->store('images')));
        return redirect()->route('optimizer');
    }
}
