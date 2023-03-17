<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_id
 * @property array $payload
 */
class WebhookEvent extends Model
{
    protected $fillable = ['payload'];
    protected $casts = [
        'payload' => 'json',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
