<?php

declare(strict_types=1);

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property int $quantity
 * @property float $price
 */
class Product extends Model
{
    use HasFactory;
    use Translatable;

    public array $translatedAttributes = ['title', 'description'];

    protected $fillable = ['image', 'quantity', 'price'];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'float',
    ];
}
