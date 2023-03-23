<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sockets;

use App\Actions\Sockets\UpdateSocketUser;
use App\Http\Requests\UpdateSocketUserRequest;
use App\Http\Resources\SocketUserResource;
use App\Models\User;

class SocketUserController
{
    public function update(
        UpdateSocketUserRequest $request,
        User $user,
        UpdateSocketUser $updateSocketUser
    ): SocketUserResource {
        $user = $updateSocketUser->update($user, $request->validated());

        return new SocketUserResource($user);
    }
}
