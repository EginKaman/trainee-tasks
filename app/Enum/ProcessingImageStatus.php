<?php

declare(strict_types=1);

namespace App\Enum;

enum ProcessingImageStatus: string
{
    case Pending = 'pending';
    case Skipped = 'skipped';
    case Success = 'success';
    case Failed = 'failed';
}
