<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Events\{ConnectedEvent, DisconnectedEvent};
use App\Http\Resources\SocketUserResource;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisSubscribeCommand extends Command
{
    protected $signature = 'redis:subscribe';

    protected $description = 'Subscribe to a Redis channels';

    public function handle(): void
    {
        $this->info('redis subscribe started');
        $redis = Redis::connection('subscriber');
        $redis->subscribe(['connected', 'disconnected'], function (string $message, string $channel): void {
            $this->info('New user connected');
            $this->info("{$channel}: {$message}");
            if ($channel === config('database.redis.options.prefix') . 'connected') {
                $this->createUser($message);
            }
            if ($channel === config('database.redis.options.prefix') . 'disconnected') {
                $this->disconnectedUser($message);
            }
        });
    }

    private function createUser(string $message): void
    {
        $data = json_decode($message);
        $user = User::factory()->create([
            'online' => true,
            'socket_id' => $data->socket,
        ]);
        $this->info($message);

        broadcast(new ConnectedEvent($data, new SocketUserResource($user)));
    }

    private function disconnectedUser(string $message): void
    {
        $data = json_decode($message);
        $user = User::where('socket_id', $data->socket)->first();
        $user->socket_id = null;
        $user->online = false;
        $user->save();

        $this->info($message);

        broadcast(new DisconnectedEvent($data, new SocketUserResource($user)));
    }
}
