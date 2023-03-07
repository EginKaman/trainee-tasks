<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SubscriptionUser extends Pivot
{
    protected $fillable = ['expired_at'];
}
