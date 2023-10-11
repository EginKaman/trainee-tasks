<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Duel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Duel */
class DuelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'created_at' => $this->created_at,

            'users' => TournamentUserResource::collection($this->whenLoaded('users')),
            'winner' => new TournamentUserResource($this->whenLoaded('winner')),
        ];
    }
}
