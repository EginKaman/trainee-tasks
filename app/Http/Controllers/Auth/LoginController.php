<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\Login;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\{LoginRequest, VerifyRequest};
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function login(LoginRequest $request, Login $login): JsonResponse
    {
        return $login->link($request->validated('email'));
    }

    public function verify(VerifyRequest $request, Login $login): JsonResponse|UserResource
    {
        return $login->login($request->validated('token'));
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json([
            'message' => __('You successfully logged out'),
        ]);
    }
}
