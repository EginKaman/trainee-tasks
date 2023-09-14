<?php

declare(strict_types=1);

namespace App\Enum;

enum WorkerStatus: string
{
    case InWork = 'in_work';
    case Finished = 'finished';
    case Error = 'error';
    case Stopped = 'stopped';
}
