<?php

declare(strict_types=1);

namespace App\Enum;

enum NotificationStatus: string
{
    case Success = 'success';
    case Failed = 'failed';
    case Pending = 'pending';
}
