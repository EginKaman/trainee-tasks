<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreFormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FormController extends Controller
{
    public function index(): View
    {
        return view('form');
    }

    public function store(StoreFormRequest $request): RedirectResponse
    {
        return redirect()->route('form.index')->withInput($request->validated())->with('success', true);
    }
}
