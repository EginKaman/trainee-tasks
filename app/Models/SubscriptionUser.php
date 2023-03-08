<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, Pivot};

class SubscriptionUser extends Pivot
{
    public $incrementing = true;
    protected $fillable = ['method', 'method_id', 'status', 'expired_at', 'started_at'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
