<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enum\MediaEnum;
use App\Models\Person;
use App\Services\MovieDbService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\{Arr, Str};
use JsonException;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        $type = MediaEnum::Person;

        $movieDbClient = new MovieDbService();

        try {
            $personDataDump = json_decode(
                Storage::get(Str::plural($type->value) . '.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            $this->command->error($e->getMessage());

            return;
        }

        $this->command->withProgressBar($personDataDump, function ($personDetails) use ($movieDbClient, $type): void {
            $personDetails = $movieDbClient->details($type->value, $personDetails['id']);
            if (empty($personDetails['birthday'])) {
                $personDetails['birthday'] = null;
            }
            if (empty($personDetails['deathday'])) {
                $personDetails['deathday'] = null;
            }
            if (empty($personDetails['homepage'])) {
                $personDetails['homepage'] = null;
            }
            if (empty($personDetails['biography'])) {
                $personDetails['biography'] = null;
            }
            if (empty($personDetails['place_of_birth'])) {
                $personDetails['place_of_birth'] = null;
            }

            Person::updateOrCreate([
                'id' => Arr::get($personDetails, 'id'),
            ], Arr::only($personDetails, [
                'adult',
                'also_known_as',
                'biography',
                'birthday',
                'deathday',
                'gender',
                'homepage',
                'imdb_id',
                'known_for_department',
                'name',
                'place_of_birth',
                'popularity',
            ]));
        });
    }
}
