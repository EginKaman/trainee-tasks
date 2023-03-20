<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, Pivot};
use Illuminate\Support\Carbon;

/**
 * @property int $user_id
 * @property int $subscription_id
 * @property string $method
 * @property string $method_id
 * @property string $status
 * @property ?Carbon $expired_at
 * @property ?Carbon $canceled_at
 * @property ?Carbon $started_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SubscriptionUser extends Pivot
{
    protected $fillable = ['method', 'method_id', 'status', 'expired_at', 'started_at', 'canceled_at'];

    protected $casts = [
        'canceled_at' => 'datetime',
        'expired_at' => 'datetime',
        'started_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
