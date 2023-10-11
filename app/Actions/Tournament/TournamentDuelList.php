<?php

declare(strict_types=1);

namespace App\Actions\Tournament;

use App\Models\Tournament;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TournamentDuelList
{
    public function execute(Tournament $tournament): LengthAwarePaginator
    {
        return $tournament->duels()->with(['users', 'winner'])->paginate();
    }
}
