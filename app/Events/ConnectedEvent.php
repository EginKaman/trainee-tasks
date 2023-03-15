<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\{Channel, InteractsWithSockets};
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConnectedEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public object $data;
    public User $user;

    public function __construct(object $data, User $user)
    {
        $this->data = $data;
        $this->user = $user;
        $this->socket = $this->data->socket;
    }

    public function broadcastOn(): array
    {
        return [new Channel('users.add')];
    }
}
