<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AsyncApiController extends Controller
{
    public function index(): BinaryFileResponse
    {
        return response()->file(resource_path('async/index.html'));
    }
}
