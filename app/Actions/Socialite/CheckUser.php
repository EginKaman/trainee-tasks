<?php

declare(strict_types=1);

namespace App\Actions\Socialite;

use App\Actions\User\ResizePhoto;
use App\Http\Resources\UserResource;
use App\Models\{User as UserModel, UserProvider};
use Illuminate\Support\Facades\{Http, Storage};
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User;

class CheckUser
{
    public function __construct(
        private ResizePhoto $resizePhoto
    ) {
    }

    public function checkOrCreate(User $socialiteUser, string $driver): UserResource
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
            $userProvider->token = $socialiteUser->token;
            $userProvider->refresh_token = $socialiteUser->refreshToken;
            $userProvider->save();

            return $this->responseUserWithToken($userProvider->user);
        }

        $user = UserModel::query()->where('email', $socialiteUser->getEmail())->firstOrNew([
            'email' => $socialiteUser->getEmail(),
            'name' => $socialiteUser->getName(),
        ]);

        //if user exists but didn't have provider
        if ($user->exists) {
            $userProvider->token = $socialiteUser->token;
            $userProvider->refresh_token = $socialiteUser->refreshToken;
            $userProvider->user()->associate($user);
            $userProvider->save();

            return $this->responseUserWithToken($user);
        }

        if ($socialiteUser->getAvatar() !== null) {
            $path = 'users/' . Str::random(40) . '.jpg';
            Storage::disk('public')->put($path, Http::get($socialiteUser->getAvatar())->body());
            $user->photo_big = $this->resizePhoto->resize('public/' . $path, 70, 70);
            $user->photo_small = $this->resizePhoto->resize('public/' . $path, 38, 38, 'small');
        }

        $user->save();

        $userProvider->user()->associate($user);
        $userProvider->save();

        return $this->responseUserWithToken($user);
    }

    private function responseUserWithToken(UserModel $user): UserResource
    {
        $additional = [
            /** @phpstan-ignore-next-line */
            'access_token' => auth('api')->login($user),
            'token_type' => 'bearer',
            /** @phpstan-ignore-next-line */
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
        if ($user->wasRecentlyCreated) {
            $additional['message'] = __('You are successfully first-step register, go by two-step register');
        }

        return (new UserResource($user))->additional($additional);
    }
}
