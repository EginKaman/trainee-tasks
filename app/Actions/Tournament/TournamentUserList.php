<?php

declare(strict_types=1);

namespace App\Actions\Tournament;

use App\Models\Tournament;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TournamentUserList
{
    public function execute(Tournament $tournament): LengthAwarePaginator
    {
        return $tournament->users()
            ->addSelect(DB::raw('row_number() over (order by score desc) as place'))
            ->orderByPivot('score', 'desc')
            ->orderByPivot('created_at', 'desc')
            ->paginate();
    }
}
