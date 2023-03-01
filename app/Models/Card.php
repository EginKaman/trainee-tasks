<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    protected $fillable = ['type', 'fingerprint', 'last_numbers'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
