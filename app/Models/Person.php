<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Laravel\Scout\Searchable;

class Person extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'adult',
        'also_known_as',
        'biography',
        'birthday',
        'deathday',
        'gender',
        'homepage',
        'imdb_id',
        'known_for_department',
        'name',
        'place_of_birth',
        'popularity',
    ];

    protected $casts = [
        'adult' => 'boolean',
        'also_known_as' => 'array',
        'birthday' => 'datetime',
        'deathday' => 'datetime',
        'popularity' => 'float',
        'gender' => 'integer',
    ];
}
