<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentHistory extends Model
{
    protected $fillable = ['status'];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
