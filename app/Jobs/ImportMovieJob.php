<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enum\MediaEnum;
use App\Models\{Country, Movie};
use App\Services\MovieDbService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ImportMovieJob implements ShouldQueue
{
    use Dispatchable; use InteractsWithQueue; use Queueable; use SerializesModels;

    private MovieDbService $movieDbClient;

    public function __construct(
        public array $movieIds
    )
    {
        $this->movieDbClient = new MovieDbService();
    }

    public function handle(): void
    {
        $type = MediaEnum::Tv;
        DB::beginTransaction();
        foreach ($this->movieIds as $movieId) {
            try {
                $details = $this->movieDbClient->details($type->value, $movieId['id']);
            } catch (\Exception $e) {
                self::dispatch([$movieId])->onQueue('import');
                \Log::warning($e->getMessage(), [
                    'movieId' => $movieId,
                ]);
            }
            if (empty($details['release_date'])) {
                $details['release_date'] = null;
            }
            if (empty($details['homepage'])) {
                $details['homepage'] = null;
            }
            if (empty($details['overview'])) {
                $details['overview'] = null;
            }
            if (empty($details['tagline'])) {
                $details['tagline'] = null;
            }
            if (empty($details['adult'])) {
                $details['adult'] = false;
            }
            if (empty($details['imdb_id'])) {
                $details['imdb_id'] = null;
            }

            $movie = Movie::updateOrCreate([
                'id' => Arr::get($details, 'id'),
            ], Arr::only($details, [
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

            $movie->genres()->sync(Arr::map(Arr::get($details, 'genres'), fn ($genre) => $genre['id']));
            $movie->countries()->sync(Country::whereIn(
                'iso_3166_1',
                Arr::map(Arr::get($details, 'production_countries'), fn ($country) => $country['iso_3166_1'])
            )->get());
        }

        try {
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
