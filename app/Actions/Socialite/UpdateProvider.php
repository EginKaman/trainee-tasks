<?php

declare(strict_types=1);

namespace App\Actions\Socialite;

use App\Models\{User, UserProvider};
use Laravel\Socialite\Two\User as SocialiteUser;

class UpdateProvider
{
    public function update(UserProvider $userProvider, SocialiteUser $socialiteUser, ?User $user = null): UserProvider
    {
        $userProvider->token = $socialiteUser->token;
        $userProvider->refresh_token = $socialiteUser->refreshToken;

        if ($user !== null) {
            $userProvider->user()->associate($user);
        }

        $userProvider->save();

        return $userProvider;
    }
}
