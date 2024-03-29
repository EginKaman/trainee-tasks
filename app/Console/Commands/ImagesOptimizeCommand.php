<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enum\ProcessingImageStatus;
use App\Jobs\Images\OptimizeJob;
use App\Models\ProcessingImage;
use Illuminate\Console\Command;

class ImagesOptimizeCommand extends Command
{
    protected $signature = 'images:optimize';

    protected $description = 'Run image optimization with "pending" status.';

    public function handle(): void
    {
        ProcessingImage::query()->where('status', ProcessingImageStatus::Pending)
            ->chunk(15, function ($images): void {
                $images->each(function (ProcessingImage $image): void {
                    $this->info($image->path);
                    OptimizeJob::dispatch($image);
                });
            });
    }
}
