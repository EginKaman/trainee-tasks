<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

class ConvertorController extends Controller
{
    public function index()
    {
        return view('convertor');
    }

    public function store(Request $request)
    {
        return redirect()->route('convertor');
    }
}
