<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, Pivot};

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
