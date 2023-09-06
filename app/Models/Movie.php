<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Relations\BelongsToMany, SoftDeletes};
use Laravel\Scout\Searchable;

class Movie extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'adult',
        'budget',
        'homepage',
        'imdb_id',
        'original_language',
        'original_title',
        'overview',
        'popularity',
        'release_date',
        'revenue',
        'runtime',
        'status',
        'tagline',
        'title',
        'vote_average',
        'vote_count',
    ];

    protected $casts = [
        'adult' => 'boolean',
        'budget' => 'integer',
        'popularity' => 'float',
        'release_date' => 'datetime',
        'revenue' => 'integer',
        'runtime' => 'integer',
        'vote_average' => 'float',
        'vote_count' => 'integer',
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }

    public function toSearchableArray(): array
    {
        return $this->loadMissing(['genres', 'countries'])->toArray();
    }
}
