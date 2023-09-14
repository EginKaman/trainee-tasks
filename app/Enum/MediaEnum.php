<?php

declare(strict_types=1);

namespace App\Enum;

enum MediaEnum: string
{
    case Movie = 'movie';
    case Tv = 'tv';
    case Person = 'person';
}
