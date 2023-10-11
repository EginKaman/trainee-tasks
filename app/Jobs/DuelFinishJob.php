<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enum\DuelStatus;
use App\Models\{Activity, Duel, DuelUser, Tournament, TournamentUser};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Str;

class DuelFinishJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Tournament $tournament
    ) {
    }

    public function handle(): void
    {
        $duelsQuery = $this->tournament->duels()->where('status', '=', DuelStatus::Started)
            ->orderBy('started_at')
            ->limit(500)
            ->where('started_at', '<=', now()->subMinutes(5));

        $duelUsers = DuelUser::whereIn('duel_id', $duelsQuery->pluck('id'))
            ->with(['user', 'duel'])
            ->orderBy('duel_id')
            ->get()
            ->groupBy('duel_id');

        $winners = [];
        $updateUsers = collect();
        $updateTournamentUsers = collect();
        $activity = collect();
        foreach ($duelUsers as $duelId => $users) {
            /** @var DuelUser $winner */
            $winner = $users->random();
            $finishedAt = $winner->duel->started_at->addMinutes(5);
            $loser = $users->where('user_id', '!=', $winner->user_id)->first();
            $winners[$duelId] = $winner->user_id;
            $resultScore = mt_rand(5, 100);
            $updateUsers->push($users->map(fn (DuelUser $duelUser) => [
                'duel_id' => $duelUser->duel_id,
                'user_id' => $duelUser->user_id,
                'result_score' => $duelUser->user_id === $winner->user_id ? $resultScore : $resultScore * -1,
            ]));
            $updateTournamentUsers->push($users->map(fn (DuelUser $duelUser) => [
                'tournament_id' => $this->tournament->id,
                'user_id' => $duelUser->user_id,
                'score' => \DB::raw(
                    'score + ' . $duelUser->user_id === $winner->user_id ? $resultScore : $resultScore * -1
                ),
            ]));

            $activity->push([
                'id' => Str::orderedUuid(),
                'user_id' => $winner->user_id,
                'tournament_id' => $this->tournament->id,
                'type' => 'duel_finish',
                'properties' => json_encode([
                    'challenge_user' => $loser,
                    'result_score' => $resultScore,
                ]),
                'created_at' => $finishedAt,
                'updated_at' => $finishedAt,
            ]);

            $activity->push([
                'id' => Str::orderedUuid(),
                'user_id' => $loser->user_id,
                'tournament_id' => $this->tournament->id,
                'type' => 'duel_finish',
                'properties' => json_encode([
                    'result_score' => $resultScore * -1,
                ]),
                'created_at' => $finishedAt,
                'updated_at' => $finishedAt,
            ]);
        }

        DuelUser::upsert($updateUsers->flatten(1)->toArray(), ['duel_id', 'user_id'], ['result_score']);
        TournamentUser::upsert($updateTournamentUsers->flatten(1)->toArray(), ['tournament_id', 'user_id'], ['score']);

        Duel::upsert($duelsQuery->get()->map(fn (Duel $duel) => [
            'id' => $duel->id,
            'tournament_id' => $duel->tournament_id,
            'status' => DuelStatus::Finished,
            'finished_at' => $duel->started_at->addMinutes(5),
            'winner_id' => $winners[$duel->id],
        ])->toArray(), ['id'], ['status', 'finished_at', 'winner_id']);
        Activity::insert($activity->toArray());
    }
}
