<?php

declare(strict_types=1);

namespace App\Actions\Socialite;

use App\Actions\User\ResizePhoto;
use App\Models\User;
use Illuminate\Support\Facades\{Http, Storage};
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User as SocialiteUser;

class CreateUser
{
    public function __construct(
        private ResizePhoto $resizePhoto
    ) {
    }

    public function create(User $user, SocialiteUser $socialiteUser): User
    {
        if ($socialiteUser->getAvatar() !== null) {
            $path = 'users/' . Str::random(40) . '.jpg';
            Storage::disk('public')->put($path, Http::get($socialiteUser->getAvatar())->body());
            $user->photo_big = $this->resizePhoto->resize('public/' . $path, 70, 70);
            $user->photo_small = $this->resizePhoto->resize('public/' . $path, 38, 38, 'small');
        }

        $user->save();

        return $user;
    }
}
