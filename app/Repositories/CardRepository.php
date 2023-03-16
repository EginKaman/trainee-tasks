<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\{Card, User};
use Illuminate\Database\Eloquent\Collection;

class CardRepository
{
    public static function getUserCards(User $user): Collection
    {
        return $user->cards()->get();
    }

    public static function getUserCard(User $user, int $cardId): Card
    {
        return Card::where('user_id', $user->id)->findOrFail($cardId);
    }
}
