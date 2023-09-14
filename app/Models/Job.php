<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\{JobStatus, JobType};
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, Relations\BelongsTo, Relations\HasMany, SoftDeletes};

class Job extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ['name', 'type', 'count_workers', 'cron', 'is_loop', 'pause', 'repetitions', 'status'];

    protected $casts = [
        'is_loop' => 'boolean',
        'count_workers' => 'integer',
        'repetitions' => 'integer',
        'pause' => 'integer',
        'status' => JobStatus::class,
        'type' => JobType::class,
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }
}
