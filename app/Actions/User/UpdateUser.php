<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;

class UpdateUser
{
    public function __construct(
        private ResizePhoto $resizePhoto
    ) {
    }

    public function update(array $data, User $user): User
    {
        $user->fill($data);
        if (isset($data['photo'])) {
            $photo = $data['photo']->store('users');

            $user->photo_big = $this->resizePhoto->resize($photo, 70, 70);
            $user->photo_small = $this->resizePhoto->resize($photo, 38, 38, 'small');
        }

        $user->role()->associate($data['role_id']);

        if ($user->isDirty()) {
            $user->save();
        }

        return $user;
    }
}
