<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enum\MediaEnum;
use App\Jobs\ImportPersonJob;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\{Arr, Str};
use JsonException;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        $type = MediaEnum::Person;

        try {
            $personDataDump = array_chunk(json_decode(
                Storage::get(Str::plural($type->value) . '.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            ), 512);
        } catch (JsonException $e) {
            $this->command->error($e->getMessage());

            return;
        }

        $this->command->withProgressBar($personDataDump, function ($personDetails): void {
           ImportPersonJob::dispatch([$personDetails])->onQueue('import');
        });
    }
}
