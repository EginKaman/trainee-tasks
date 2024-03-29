<?php

declare(strict_types=1);

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Casts\Attribute, Model, SoftDeletes};
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property int $quantity
 * @property float $price
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Translatable;

    public array $translatedAttributes = ['title', 'description'];

    protected $fillable = ['image', 'quantity', 'price'];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'integer',
    ];

    public function image(): Attribute
    {
        return Attribute::make(get: function ($value, $attributes) {
            if ($value !== null && Storage::exists($attributes['image'])) {
                return $value;
            }

            return 'public/no-image.jpeg';
        });
    }
}
