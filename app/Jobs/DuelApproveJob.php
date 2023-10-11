<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enum\DuelStatus;
use App\Jobs\Middleware\TimeRelease;
use App\Models\{Duel, Tournament};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class DuelApproveJob implements ShouldQueue
{
    use Dispatchable; use InteractsWithQueue; use Queueable; use SerializesModels;

    public function __construct(
        public Tournament $tournament
    )
    {
    }

    public function middleware(): array
    {
        return [new TimeRelease(now()->setTime(23, 55), now()->endOfDay())];
    }

    public function handle(): void
    {
        $duels = $this->tournament->duels()->where('status', '=', DuelStatus::Pending);

        $duels->where('status', '=', DuelStatus::Pending)
            ->limit((int) round($duels->count() * 0.9))
            ->update([
                'status' => DuelStatus::Started,
                'started_at' => now(),
            ]);

        $duels->where('status', '=', DuelStatus::Pending)->update([
            'status' => DuelStatus::Canceled,
        ]);
    }
}
