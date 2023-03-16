<?php

declare(strict_types=1);

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $product_id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property int $quantity
 * @property float $price
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class OrderProduct extends Model
{
    use Translatable;
    public array $translatedAttributes = ['title', 'description'];
    protected string $translationForeignKey = 'product_id';

    protected $fillable = ['image', 'quantity', 'price'];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'float',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
