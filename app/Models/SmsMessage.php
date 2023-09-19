<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\NotificationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsMessage extends Model
{
    use HasFactory; use HasUuids;

    protected $fillable = ['id', 'phone', 'text', 'status', 'channel'];

    protected $casts = [
        'id' => 'string',
        'phone' => 'string',
        'text' => 'string',
        'status' => NotificationStatus::class,
        'channel' => 'string',
    ];
}
