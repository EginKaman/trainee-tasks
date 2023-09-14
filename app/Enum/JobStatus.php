<?php

declare(strict_types=1);

namespace App\Enum;

enum JobStatus: string
{
    case Created = 'created';
    case Running = 'running';
    case Stopped = 'stopped';
    case Finished = 'finished';
    case Failed = 'failed';
}
