<?php

declare(strict_types=1);

namespace App\Enum;

enum DomainType: string
{
    case CPE = 'cpe';
    case CPA = 'cpa';
}
