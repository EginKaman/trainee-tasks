<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enum\MediaEnum;
use App\Models\{Country, Serial};
use App\Services\MovieDbService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\{Arr, Str};
use JsonException;

class SerialSeeder extends Seeder
{
    public function run(): void
    {
        $type = MediaEnum::Tv;

        $movieDbClient = new MovieDbService();

        try {
            $serialsDataDump = json_decode(
                Storage::get(Str::plural($type->value) . '.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            $this->command->error($e->getMessage());

            return;
        }

        $this->command->withProgressBar($serialsDataDump, function ($movieDetails) use ($movieDbClient, $type): void {
            $movieDetails = $movieDbClient->details($type->value, $movieDetails['id']);
            if (empty($movieDetails['first_air_date'])) {
                $movieDetails['first_air_date'] = null;
            }
            if (empty($movieDetails['last_air_date'])) {
                $movieDetails['last_air_date'] = null;
            }
            if (empty($movieDetails['homepage'])) {
                $movieDetails['homepage'] = null;
            }
            if (empty($movieDetails['overview'])) {
                $movieDetails['overview'] = null;
            }
            if (empty($movieDetails['tagline'])) {
                $movieDetails['tagline'] = null;
            }

            $serial = Serial::updateOrCreate([
                'id' => Arr::get($movieDetails, 'id'),
            ], Arr::only($movieDetails, [
                'adult',
                'budget',
                'first_air_date',
                'homepage',
                'in_production',
                'last_air_date',
                'name',
                'number_of_episodes',
                'number_of_seasons',
                'original_language',
                'original_name',
                'overview',
                'popularity',
                'runtime',
                'revenue',
                'status',
                'tagline',
                'type',
                'vote_average',
                'vote_count',
            ]));
            $serial->genres()->sync(Arr::map(Arr::get($movieDetails, 'genres'), fn ($genre) => $genre['id']));
            $serial->countries()->sync(
                Country::query()
                    ->whereIn('iso_3166_1', Arr::get($movieDetails, 'origin_country', []))
                    ->pluck('id')
            );
        });
    }
}
