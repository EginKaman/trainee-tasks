<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Laravel\Scout\Searchable;

class Serial extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'adult',
        'budget',
        'first_air_date',
        'homepage',
        'in_production',
        'last_air_date',
        'name',
        'number_of_episodes',
        'number_of_seasons',
        'original_language',
        'original_name',
        'overview',
        'popularity',
        'runtime',
        'revenue',
        'status',
        'tagline',
        'type',
        'vote_average',
        'vote_count',
    ];

    protected $casts = [
        'adult' => 'boolean',
        'budget' => 'integer',
        'first_air_date' => 'datetime',
        'last_air_date' => 'datetime',
        'popularity' => 'float',
        'revenue' => 'integer',
        'runtime' => 'integer',
        'vote_average' => 'float',
        'vote_count' => 'integer',
        'in_production' => 'boolean',
    ];

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function toSearchableArray(): array
    {
        return $this->loadMissing(['genres', 'countries'])->toArray();
    }
}
