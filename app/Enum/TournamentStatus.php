<?php

declare(strict_types=1);

namespace App\Enum;

enum TournamentStatus: string
{
    case Created = 'created';
    case Started = 'started';
    case Finished = 'finished';
}
