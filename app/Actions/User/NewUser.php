<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;

class NewUser
{
    public function store(StoreUserRequest $request): User
    {
        $user = new User($request->validated());

        if ($request->hasFile('photo')) {
            $photo = $request->photo->store('users');

            $user->photo_big = app(ResizePhoto::class)->resize($photo, 70, 70);
            $user->photo_small = app(ResizePhoto::class)->resize($photo, 38, 38, 'small');
        }
        $user->role()->associate($request->validated('role_id'));
        $user->createdUser()->associate(1);
        $user->updatedUser()->associate(1);

        $user->save();

        return $user;
    }
}
