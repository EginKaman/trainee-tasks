<?php

declare(strict_types=1);

namespace App\Actions\Sockets;

use App\Actions\User\ResizePhoto;
use App\Events\UserUpdateEvent;
use App\Http\Resources\SocketUserResource;
use App\Models\User;

class UpdateSocketUser
{
    public function __construct(
        private ResizePhoto $resizePhoto
    ) {
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data);
        if (isset($data['photo'])) {
            $photo = $data['photo']->store('users');

            $user->photo_big = $this->resizePhoto->resize($photo, 70, 70);
            $user->photo_small = $this->resizePhoto->resize($photo, 50, 50, 'small');
        }

        if ($user->isDirty()) {
            $user->save();

            broadcast(new UserUpdateEvent(new SocketUserResource($user), $user->socket_id));
        }

        return $user;
    }
}
