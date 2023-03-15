<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * @property int $id
 * @property int $payment_id
 * @property float $amount
 * @property string $status
 */
class Order extends Model
{
    protected $fillable = ['amount', 'status'];

    protected $casts = [
        'amount' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
