<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $payment_id
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PaymentHistory extends Model
{
    protected $fillable = ['status'];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
