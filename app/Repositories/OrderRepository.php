<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    public static function getUserOrders(User $user): Collection
    {
        return $user->orders()->with(['products', 'products.translation'])->get();
    }
}
