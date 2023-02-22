<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        return response()->json([
            'status' => __('Success'),
            'message' => __('A message with an authorization link was successfully sent to your email'),
        ]);
    }

    public function logout(): JsonResponse
    {
        return response()->json([
            'message' => 'You successfully logged out"',
        ]);
    }
}
