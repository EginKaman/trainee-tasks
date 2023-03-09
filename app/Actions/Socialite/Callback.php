<?php

declare(strict_types=1);

namespace App\Actions\Socialite;

use App\Models\{User, User as UserModel, UserProvider};
use Laravel\Socialite\Two\User as SocialiteUser;

class Callback
{
    public function __construct(
        private UpdateProvider $provider,
        private CreateUser $createUser
    ) {
    }

    public function callback(SocialiteUser $socialiteUser, string $driver): User
    {
        $userProvider = UserProvider::query()->with('user')
            ->where('driver', $driver)
            ->where('driver_id', $socialiteUser->getId())
            ->firstOrNew([
                'driver' => $driver,
                'driver_id' => $socialiteUser->getId(),
                'token' => $socialiteUser->token,
                'refresh_token' => $socialiteUser->refreshToken,
            ]);

        //if user is attached to provider
        if ($userProvider->exists) {
            $userProvider = $this->provider->update($userProvider, $socialiteUser);

            return $userProvider->user;
        }

        $user = UserModel::query()->where('email', $socialiteUser->getEmail())->firstOrNew([
            'email' => $socialiteUser->getEmail(),
            'name' => $socialiteUser->getName(),
        ]);

        //if user exists but didn't have provider
        if ($user->exists) {
            $this->provider->update($userProvider, $socialiteUser, $user);

            return $user;
        }

        $user = $this->createUser->create($user, $socialiteUser);

        $this->provider->update($userProvider, $socialiteUser, $user);

        return $user;
    }
}
