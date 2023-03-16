<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\{Channel, InteractsWithSockets};
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Queue\SerializesModels;

class UserListEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public AnonymousResourceCollection $users
    ) {
    }

    public function broadcastOn(): array
    {
        return [new Channel('users')];
    }
}
