<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Mail\LoginMail;
use App\Models\{LoginToken, User};
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginLink
{
    public function create(?string $email = null): void
    {
        $user = User::query()->where('email', $email)->first();

        $token = new LoginToken([
            'token' => Str::random(32),
            'expired_at' => now()->addHours(24),
        ]);
        $token->user()->associate($user);
        $token->save();

        Mail::send(new LoginMail($user, $token->token));
    }
}
