<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\LoginToken;

class Login
{
    public function login(?string $token = null): bool|LoginToken
    {
        $loginToken = LoginToken::query()->with('user')->where('token', $token)->first();
        if ($loginToken->consumed_at === null && $loginToken->expired_at > now()) {
            $loginToken->consumed_at = now();
            $loginToken->save();

            return $loginToken;
        }

        return false;
    }
}
