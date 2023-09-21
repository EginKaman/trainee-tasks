<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Duel extends Model
{
    use HasFactory; use HasUuids;

    protected $fillable = ['status', 'finished_at', ];

    protected $casts = [
        'finished_at' => 'datetime',
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
