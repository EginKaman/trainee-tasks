<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    use HasFactory; use HasUuids;

    protected $fillable = ['name', 'description', 'properties'];

    protected $casts = [
        'properties' => 'collection',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
