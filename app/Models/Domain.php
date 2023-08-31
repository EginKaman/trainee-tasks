<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\DomainType;
use Illuminate\Database\Eloquent\{Model, Relations\BelongsTo, SoftDeletes};

class Domain extends Model
{
    use SoftDeletes;

    protected $fillable = ['country_id', 'type', 'domain', 'is_available'];

    protected $casts = [
        'is_available' => 'boolean',
        'type' => DomainType::class,
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
