<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enum\MediaEnum;
use App\Models\{Country, Movie};
use App\Services\MovieDbService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\{Arr, Str};
use JsonException;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $type = MediaEnum::Movie;

        $movieDbClient = new MovieDbService();

        try {
            $movieDataDump = json_decode(
                Storage::get(Str::plural($type->value) . '.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            $this->command->error($e->getMessage());

            return;
        }

        $this->command->withProgressBar(
            $movieDataDump,
            function ($movieDetails) use ($movieDbClient, $type): void {
                $movieDetails = $movieDbClient->details($type->value, $movieDetails['id']);
                if (empty($movieDetails['release_date'])) {
                    $movieDetails['release_date'] = null;
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

                $movie = Movie::updateOrCreate([
                    'id' => Arr::get($movieDetails, 'id'),
                ], Arr::only($movieDetails, [
                    'adult',
                    'budget',
                    'homepage',
                    'imdb_id',
                    'original_language',
                    'original_title',
                    'overview',
                    'popularity',
                    'release_date',
                    'revenue',
                    'runtime',
                    'status',
                    'tagline',
                    'title',
                    'vote_average',
                    'vote_count',
                ]));

                $movie->genres()->sync(Arr::map(Arr::get($movieDetails, 'genres'), fn ($genre) => $genre['id']));
                $movie->countries()->sync(Country::whereIn(
                    'iso_3166_1',
                    Arr::map(Arr::get($movieDetails, 'production_countries'), fn ($country) => $country['iso_3166_1'])
                )->get());
            }
        );
    }
}
