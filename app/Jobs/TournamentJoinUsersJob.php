<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\{Activity, Tournament, TournamentUser, User};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TournamentJoinUsersJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Tournament $tournament,
        public int $minimumUsers = 60000
    ) {
        $this->tournament->loadCount('users');
        $this->minimumUsers();
    }

    public function handle(): void
    {
        $users = User::inRandomOrder()
            ->whereDoesntHave('tournaments', fn ($query) => $query->where('id', '=', $this->tournament->id))
            ->limit(mt_rand($this->minimumUsers, (int) round($this->minimumUsers * 1.65421)))
            ->pluck('id')
            ->chunk(2000);
        $users->each(function ($users): void {
            DB::transaction(function () use ($users): void {
                TournamentUser::insert($users->map(fn (
                    $id
                ) => [
                    'tournament_id' => $this->tournament->id,
                    'user_id' => $id,
                    'score' => mt_rand(-100, 1000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray());
                Activity::insert($users->map(fn (
                    $id
                ) => [
                    'id' => Str::orderedUuid(),
                    'user_id' => $id,
                    'tournament_id' => $this->tournament->id,
                    'type' => 'tournament_join',
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray());
            });
        });
    }

    protected function minimumUsers(): void
    {
        if ($this->tournament->created_at->diffInHours(now()) < 1 && $this->tournament->users_count < 30000) {
            $this->minimumUsers = mt_rand(50, 500);
        } elseif ($this->tournament->created_at->diffInHours(now()) > 1 && $this->tournament->users_count > 60000) {
            $this->minimumUsers = mt_rand(100, 500);
        } else {
            $this->minimumUsers = mt_rand(10, 100);
        }
    }
}
