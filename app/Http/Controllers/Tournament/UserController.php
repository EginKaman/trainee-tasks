<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tournament;

use App\Actions\Tournament\TournamentUserList;
use App\Http\Controllers\Controller;
use App\Http\Resources\TournamentUserResource;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function __invoke(
        Request $request,
        Tournament $tournament,
        TournamentUserList $tournamentUserList
    ): JsonResource {
        return TournamentUserResource::collection($tournamentUserList->execute($tournament));
    }
}
