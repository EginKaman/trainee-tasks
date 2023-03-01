<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Payment extends Model
{
    protected $fillable = ['method', 'method_id', 'amount', 'currency', 'status'];

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
