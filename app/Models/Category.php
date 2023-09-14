<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, Relations\BelongsTo, Relations\HasMany, SoftDeletes};

class Category extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ['title'];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function bots(): HasMany
    {
        return $this->hasMany(Bot::class);
    }
}
