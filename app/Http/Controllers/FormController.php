<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

class FormController extends Controller
{
    public function index(): View
    {
        return view('form');
    }

    public function store(): void
    {
    }
}
