<?php

declare(strict_types=1);

namespace App\Enum;

enum OrderStatus: string
{
    case Created = 'created';
    case PaymentPending = 'payment_pending';
    case NotPayed = 'not_payed';
    case Refunded = 'refunded';
    case PaymentSuccess = 'payment_success';
}
