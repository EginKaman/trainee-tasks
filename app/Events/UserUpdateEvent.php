<?php

declare(strict_types=1);

namespace App\Events;

use App\Http\Resources\SocketUserResource;
use Illuminate\Broadcasting\{Channel, InteractsWithSockets};
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserUpdateEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public SocketUserResource $user;

    public function __construct(SocketUserResource $user, string $socket)
    {
        $this->user = $user;
        $this->socket = $socket;
    }

    public function broadcastOn(): array
    {
        return [new Channel('users')];
    }
}
