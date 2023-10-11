<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tournament;

use App\Actions\Tournament\TournamentDuelList;
use App\Http\Controllers\Controller;
use App\Http\Resources\DuelResource;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DuelController extends Controller
{
    public function __invoke(
        Request $request,
        Tournament $tournament,
        TournamentDuelList $duelList
    ): AnonymousResourceCollection
    {
        return DuelResource::collection($duelList->execute($tournament));
    }
}
