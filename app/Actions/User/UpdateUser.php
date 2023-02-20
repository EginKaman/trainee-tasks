<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Http\Requests\{StoreUserRequest, UpdateUserRequest};
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UpdateUser
{
    public function update(UpdateUserRequest $request, User $user): User
    {
        $user->fill($request->validated());
        if ($request->hasFile('photo')) {
            $photo = $request->photo->store('users');

            $user->photo_big = app(ResizePhoto::class)->resize($photo, 70, 70);
            $user->photo_small = app(ResizePhoto::class)->resize($photo, 38, 38, 'small');
        }
        if ($user->isDirty()) {
            $user->save();
            Cache::tags('users')->flush();
        }

        return $user;
    }
}
