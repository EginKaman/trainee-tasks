<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function created(User $user): void
    {
        Cache::tags('users')->flush();
    }

    public function updated(User $user): void
    {
        Cache::tags('users')->flush();
    }

    public function deleted(User $user): void
    {
        Cache::tags('users')->flush();
    }

    public function restored(User $user): void
    {
        Cache::tags('users')->flush();
    }

    public function forceDeleted(User $user): void
    {
        Cache::tags('users')->flush();
    }
}
