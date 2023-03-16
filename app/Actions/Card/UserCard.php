<?php

declare(strict_types=1);

namespace App\Actions\Card;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserCard
{
    public function get(User $user): Collection
    {
        return $user->cards()->get();
    }
}
