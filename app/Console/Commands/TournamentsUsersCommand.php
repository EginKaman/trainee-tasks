<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enum\TournamentStatus;
use App\Jobs\{DuelApproveJob, DuelCreateJob, DuelFinishJob, TournamentJoinUsersJob};
use App\Models\Tournament;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class TournamentsUsersCommand extends Command
{
    protected $signature = 'tournaments:users';

    protected $description = 'Command description';

    public function handle(): void
    {
        Tournament::where('status', '=', TournamentStatus::Started)
            ->inRandomOrder()
            ->get()
            ->each(function (Tournament $tournament): void {
                Bus::chain([
                    new TournamentJoinUsersJob($tournament),
                    new DuelCreateJob($tournament),
                    new DuelApproveJob($tournament),
                ])->delay(mt_rand(0, 28))->onQueue('tournaments')->dispatch();
                DuelFinishJob::dispatch($tournament)->delay(mt_rand(0, 24))->onQueue('tournaments');
                DuelFinishJob::dispatch($tournament)->delay(mt_rand(28, 56))->onQueue('tournaments');
            });
    }
}
