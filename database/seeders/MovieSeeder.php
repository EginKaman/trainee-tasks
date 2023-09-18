<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enum\MediaEnum;
use App\Jobs\ImportMovieJob;
use App\Models\{Country, Movie};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\{Arr, Str};
use JsonException;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $type = MediaEnum::Movie;

        try {
            $movieDataDump = array_chunk(json_decode(
                Storage::get(Str::plural($type->value) . '.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            ), 512);
        } catch (JsonException $e) {
            $this->command->error($e->getMessage());

            return;
        }

        $this->command->withProgressBar(
            $movieDataDump,
            function ($movieIds): void {
                ImportMovieJob::dispatch($movieIds)->onQueue('import');
            }
        );
    }
}
