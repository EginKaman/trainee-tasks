<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\{LoginToken, User};
use Illuminate\Http\JsonResponse;

class Login
{
    public function __construct(
        private NewLogin $newLogin
    ) {
    }

    public function login(?string $email = null, ?string $token = null): JsonResponse
    {
        if ($token === null) {
            $user = User::query()->where('email', $email)->first();

            $this->newLogin->create($user);

            return response()->json([
                'status' => __('Success'),
                'message' => __('A message with an authorization link was successfully sent to your email'),
            ]);
        }
        $loginToken = LoginToken::query()->with('user')->where('token', $token)->first();
        if ($loginToken->consumed_at === null && $loginToken->expired_at > now()) {
            $loginToken->consumed_at = now();
            $loginToken->save();

            return response()->json([
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
}
