<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enum\{DuelStatus, TournamentStatus};
use App\Models\Tournament;
use Illuminate\Console\Command;

class TournamentsFinishCommand extends Command
{
    protected $signature = 'tournaments:finish';

    protected $description = 'Finish all tournaments';

    public function handle(): void
    {
        Tournament::where('status', '=', TournamentStatus::Started)
            ->each(function (Tournament $tournament): void {
                $tournament->duels()->update([
                    'status' => DuelStatus::Finished,
                    'finished_at' => now(),
                ]);
                $tournament->update([
                    'status' => TournamentStatus::Finished,
                    'finished_at' => now(),
                ]);
            });
    }
}
