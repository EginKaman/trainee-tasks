<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;

class NewUser
{
    public function __construct(
        private ResizePhoto $resizePhoto
    ) {
    }

    public function store(array $data): User
    {
        $user = new User($data);

        if ($data['photo'] instanceof UploadedFile) {
            $photo = $data['photo']->store('public/users');

            $user->photo_big = $this->resizePhoto->resize($photo, 70, 70);
            $user->photo_small = $this->resizePhoto->resize($photo, 38, 38, 'small');
        }
        $user->role()->associate($data['role_id']);
        $user->createdUser()->associate(1);
        $user->updatedUser()->associate(1);

        $user->save();

        return $user;
    }
}
