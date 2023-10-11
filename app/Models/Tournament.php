<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\TournamentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};

class Tournament extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ['status', 'finished_at', 'started_at', 'title'];

    protected $casts = [
        'finished_at' => 'datetime',
        'started_at' => 'datetime',
        'status' => TournamentStatus::class,
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(TournamentUser::class)
            ->withPivot('score')
            ->withTimestamps();
    }

    public function duels(): HasMany
    {
        return $this->hasMany(Duel::class);
    }
}
