<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property string $method
 * @property string $method_id
 * @property string $client_secret
 * @property int $amount
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Payment extends Model
{
    protected $fillable = ['method', 'method_id', 'client_secret', 'amount', 'currency', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(PaymentHistory::class);
    }
}
