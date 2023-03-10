<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\{Login, LoginLink};
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\{LoginRequest, VerifyRequest};
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function login(LoginRequest $request, LoginLink $loginLink): JsonResponse
    {
        $loginLink->create($request->validated('email'));

        return response()->json([
            'status' => __('Success'),
            'message' => __('A message with an authorization link was successfully sent to your email'),
        ]);
    }

    public function verify(VerifyRequest $request, Login $login): JsonResponse|UserResource
    {
        if ($loginToken = $login->login($request->validated('token'))) {
            return (new UserResource($loginToken->user))->additional([
                /** @phpstan-ignore-next-line */
                'access_token' => auth('api')->login($loginToken->user),
                'token_type' => 'bearer',
                /** @phpstan-ignore-next-line */
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ]);
        }

        return response()->json([
            'message' => __('Unauthorized'),
        ], 401);
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json([
            'message' => __('You successfully logged out'),
        ]);
    }
}
