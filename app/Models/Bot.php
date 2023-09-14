<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasManyThrough};

class Bot extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ['title'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function workers(): HasManyThrough
    {
        return $this->hasManyThrough(Worker::class, Job::class);
    }
}
