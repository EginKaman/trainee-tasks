<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\{Date, Storage};

class ImagesClearCommand extends Command
{
    protected $signature = 'images:clear';

    protected $description = 'Cleans images older than two days';

    public function handle(): void
    {
        $query = Image::query()->with('processingImages')
            ->where('created_at', '<', Date::now()->subDays(2));
        $query->chunk(30, function ($images): void {
            $images->each(function (Image $image): void {
                $this->info($image->path);
                Storage::delete($image->processingImages->map(fn ($processing) => $processing->path)->toArray());
                Storage::delete($image->path);
            });
        });
        $query->delete();
    }
}
