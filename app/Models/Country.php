<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $iso_2_code
 * @property string $title
 */
class Country extends Model
{
    public $timestamps = false;

    protected $fillable = ['iso_3166_1', 'english_name', 'native_name'];

    public function iso2Code(): Attribute
    {
        return Attribute::make(get: fn ($value, $attributes) => $attributes['iso_3166_1']);
    }
    public function title(): Attribute
    {
        return Attribute::make(get: fn ($value, $attributes) => $attributes['english_name']);
    }
}
