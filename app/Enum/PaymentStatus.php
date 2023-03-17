<?php

declare(strict_types=1);

namespace App\Enum;

enum PaymentStatus: string
{
    case Created = 'created';
    case Pending = 'pending';
    case Canceled = 'canceled';
    case Failed = 'failed';
    case Refunded = 'refunded';
    case Success = 'success';
}
