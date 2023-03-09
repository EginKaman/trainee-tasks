<?php

declare(strict_types=1);

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $period
 * @property float $price
 * @property SubscriptionUser $pivot
 */
class Subscription extends Model
{
    use HasFactory;
    use Translatable;

    public array $translatedAttributes = ['title', 'description'];

    protected $fillable = ['image', 'period', 'price'];

    protected $casts = [
        'price' => 'integer',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(SubscriptionUser::class);
    }
}
