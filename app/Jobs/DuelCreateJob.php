<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enum\DuelStatus;
use App\Jobs\Middleware\TimeRelease;
use App\Models\{Activity, Duel, DuelUser, Tournament};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\{Collection, Facades\DB, Str};

class DuelCreateJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Tournament $tournament,
    ) {
        $this->tournament->loadCount(['users']);
    }

    public function middleware(): array
    {
        return [new TimeRelease(now()->setTime(23, 55), now()->endOfDay())];
    }

    public function handle(): void
    {
        $users = $this->tournament->users()
            ->withCount('duels')
            ->orderBy('duels_count')
            ->limit(mt_rand(500, 1500))
            ->get();
        DB::transaction(function () use ($users): void {
            $activity = collect();
            $duelUser = collect();
            $duels = [];
            $users->chunk(2)->each(function (Collection $users) use (&$duelUser, &$duels, &$activity): void {
                if ($users->count() < 2) {
                    return;
                }
                $id = Str::orderedUuid();
                $duels[] = [
                    'id' => $id,
                    'tournament_id' => $this->tournament->id,
                    'status' => DuelStatus::Pending,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $duelUser->push($users->map(function ($user) use ($id): array {
                    return [
                        'duel_id' => $id,
                        'user_id' => $user->id,
                        'result_score' => 0,
                    ];
                }));
                $activity->push([
                    'id' => Str::orderedUuid(),
                    'user_id' => $users->first()->id,
                    'tournament_id' => $this->tournament->id,
                    'type' => 'duel_challenge',
                    'properties' => json_encode([
                        'challenge_user' => $users->last(),
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
//            dd($activity);
            Duel::insert($duels);
            DuelUser::insert($duelUser->flatten(1)->toArray());
            Activity::insert($activity->toArray());
        });
    }
}
