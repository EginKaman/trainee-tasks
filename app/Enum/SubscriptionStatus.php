<?php

declare(strict_types=1);

namespace App\Enum;

enum SubscriptionStatus: string
{
    case Created = 'created';
    case Pending = 'pending';
    case Expired = 'expired';
    case Active = 'active';
    case Canceled = 'canceled';
}
