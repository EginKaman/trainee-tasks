<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\{DuelUser, TournamentUser};
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $place
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $score
 * @property DuelUser|TournamentUser $pivot
 *
 * @see \App\Models\User
 */
class TournamentUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'place' => $this->when($this->place !== null, $this->place),
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'score' => $this->whenPivotLoaded('tournament_user', fn () => $this->pivot->score),
            'result_score' => $this->whenPivotLoaded('duel_user', fn () => $this->pivot->result_score),
        ];
    }
}
