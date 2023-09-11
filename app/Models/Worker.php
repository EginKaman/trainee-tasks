<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\WorkerStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Worker extends Model
{
    use HasFactory; use HasUuids;

    protected $fillable = ['name', 'status', 'completed_at', 'job_id'];

    protected $casts = [
        'completed_at' => 'datetime',
        'status' => WorkerStatus::class,
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
