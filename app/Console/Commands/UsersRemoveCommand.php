<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\{Schema, Storage};

class UsersRemoveCommand extends Command
{
    protected $signature = 'users:remove';

    protected $description = 'Command description';

    public function handle(): void
    {
        User::whereNotNull('photo_small')->chunk(20, function (Collection $users): void {
            Storage::delete($users->map(fn ($user) => $user->photo_small)->toArray());
            Storage::delete($users->map(fn ($user) => $user->photo_big)->toArray());
        });

        Schema::disableForeignKeyConstraints();

        User::truncate();

        Schema::enableForeignKeyConstraints();
    }
}
