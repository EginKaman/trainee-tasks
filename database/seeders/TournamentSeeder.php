<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enum\TournamentStatus;
use App\Jobs\{DuelApproveJob, DuelCreateJob, TournamentJoinUsersJob};
use App\Models\{Tournament, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{Bus, DB};

class TournamentSeeder extends Seeder
{
    public function run(): void
    {
        $startTime = now();
        $this->command->info('Started at ' . $startTime->format('Y-m-d H:i:s'));
        $lastDate = (Tournament::latest()->first()?->created_at ?? now())->toImmutable();
        $this->command->info('Last tournament created at ' . $lastDate->format('Y-m-d H:i:s'));
        $tournaments = [];
        $this->command->info('Creating 20 tournaments');
        $this->command->withProgressBar(20, function ($bar) use ($lastDate, &$tournaments): void {
            for ($i = 1; $i <= 20; ++$i) {
                $tournaments[] = Tournament::factory()->created($lastDate->subDays($i)->startOfDay())->raw();
                $bar->advance();
            }
            Tournament::insert($tournaments);
        });

        $this->command->newLine();
        $this->command->info('Creating 100000 users');
        $total = 100000;
        $this->command->withProgressBar($total, function ($bar) use ($total): void {
            DB::transaction(function () use ($bar, $total): void {
                $totalUsers = 0;
                while ($totalUsers < $total) {
                    $perChunk = min(1000, $total - $totalUsers);
                    $count = User::insertOrIgnore(User::factory($perChunk)->raw());
                    $totalUsers += $count;
                    $bar->advance($count);
                }
            });
        });
        $this->command->newLine();
//        $tournaments = Tournament::where('status', TournamentStatus::Created)
//            ->each(function (Tournament $tournament): void {
//                $this->command->newLine();
//                Bus::chain([
//                    new TournamentJoinUsersJob($tournament),
//                    new DuelCreateJob($tournament),
//                    new DuelApproveJob($tournament),
//                    new DuelFinishJob($tournament),
//                ])->onQueue('tournaments')->dispatch();
//                $this->command->info('Tournament ' . $tournament->id . ' dispatched');
//            });

        Tournament::where('status', TournamentStatus::Created)
            ->update([
                'status' => TournamentStatus::Started,
            ]);

        $this->command->info('Finished at ' . now()->format('Y-m-d H:i:s'));
        $this->command->info('Total time: ' . now()->diffForHumans($startTime));
    }
}
