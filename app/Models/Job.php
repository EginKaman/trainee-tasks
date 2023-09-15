<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\{JobStatus, JobType};
use Cron\CronExpression;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Casts\Attribute, Model, Relations\BelongsTo, Relations\HasMany};
use Illuminate\Support\Carbon;

/**
 * @property string $name
 * @property JobType $type
 * @property int $count_workers
 * @property ?string $cron
 * @property bool $is_loop
 * @property int $pause
 * @property int $repetitions
 * @property JobStatus $status
 * @property ?string $last_schedule
 */
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

    public function lastSchedule(): Attribute
    {
        return Attribute::make(
            fn ($value, $attributes) => (new Carbon((new CronExpression(
                $attributes['cron']
            ))->getPreviousRunDate()))->diffForHumans()
        );
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }
}
