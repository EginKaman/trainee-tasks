<?php

declare(strict_types=1);

namespace App\Enum;

enum JobType: string
{
   case Single = 'single';
   case Cron = 'cron';
}
