<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Mail\LoginMail;
use App\Models\{LoginToken, User};
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewLogin
{
    public function create(User $user): void
    {
        $token = new LoginToken([
            'token' => Str::random(32),
            'expired_at' => now()->addHours(2),
        ]);
        $token->user()->associate($user);
        $token->save();

        Mail::send(new LoginMail($user, $token->token));
    }
}
