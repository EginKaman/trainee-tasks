<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $last_numbers
 * @property string $type,
 * @property string $fingerprint
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Card extends Model
{
    protected $fillable = ['type', 'fingerprint', 'last_numbers'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
