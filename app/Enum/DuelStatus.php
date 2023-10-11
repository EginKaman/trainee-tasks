<?php

declare(strict_types=1);

namespace App\Enum;

enum DuelStatus: string
{
    case Pending = 'pending';
    case Canceled = 'canceled';
    case Started = 'started';
    case Finished = 'finished';
}
