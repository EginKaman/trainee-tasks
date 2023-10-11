<?php

declare(strict_types=1);

namespace App\Actions\Tournament;

use App\Models\Tournament;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TournamentList
{
    public function execute(): LengthAwarePaginator
    {
        return Tournament::paginate();
    }
}
