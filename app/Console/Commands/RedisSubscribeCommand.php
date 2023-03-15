<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Events\ConnectedEvent;
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
        $redis->subscribe(['connected'], function (string $message, string $channel): void {
            $this->info('New user connected');
            $this->info("{$channel}: {$message}");
            $this->createUser($message);
        });
    }

    public function createUser(string $message): void
    {
        $user = User::factory()->create();
        $data = json_decode($message);
        $this->info($message);
        broadcast(new ConnectedEvent($data, $user));
    }
}
