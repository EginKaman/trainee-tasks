<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, Pivot};
use Ramsey\Uuid\Uuid;

/**
 * @property int $user_id
 * @property Uuid $duel_id
 * @property int $result_score
 * @property User $user
 * @property Duel $duel
 */
class DuelUser extends Pivot
{
    public $timestamps = false;
    protected $fillable = ['result_score'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function duel(): BelongsTo
    {
        return $this->belongsTo(Duel::class);
    }
}
