<?php

declare(strict_types=1);

namespace App\Events;

use App\Http\Resources\SocketUserResource;
use Illuminate\Broadcasting\{Channel, InteractsWithSockets};
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DisconnectedEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public object $data;
    public SocketUserResource $user;

    public function __construct(object $data, SocketUserResource $user)
    {
        $this->data = $data;
        $this->user = $user;
        $this->socket = $this->data->socket;
    }

    public function broadcastOn(): array
    {
        return [new Channel('users')];
    }
}
