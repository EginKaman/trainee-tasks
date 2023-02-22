<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\Login;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function login(LoginRequest $request, Login $login): JsonResponse
    {
        return $login->login($request->validated('email'), $request->validated('token'));
    }

    public function logout(): JsonResponse
    {
        return response()->json([
            'message' => 'You successfully logged out"',
        ]);
    }
}
