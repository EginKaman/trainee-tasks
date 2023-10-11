<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tournament;

use App\Actions\Tournament\TournamentList;
use App\Http\Controllers\Controller;
use App\Http\Resources\TournamentResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TournamentController extends Controller
{
    public function __invoke(TournamentList $tournamentList): ResourceCollection
    {
        return TournamentResource::collection($tournamentList->execute());
    }
}
