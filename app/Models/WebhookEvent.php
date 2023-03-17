<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

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

    public function eventable(): MorphTo
    {
        return $this->morphTo();
    }
}
