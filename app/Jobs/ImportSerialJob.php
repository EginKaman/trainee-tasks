<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enum\MediaEnum;
use App\Models\{Country, Serial};
use App\Services\MovieDbService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Arr;

class ImportSerialJob implements ShouldQueue
{
    use Dispatchable; use InteractsWithQueue; use Queueable; use SerializesModels;

    private MovieDbService $movieDbClient;

    public function __construct(
        public array $serialIds
    )
    {
        $this->movieDbClient = new MovieDbService();
    }

    public function handle(): void
    {
        $type = MediaEnum::Tv;
        foreach ($this->serialIds as $serialId) {
            try {
                $details = $this->movieDbClient->details($type->value, $serialId['id']);
            } catch (\Exception $e) {
                self::dispatch([$serialId])->onQueue('import');
                \Log::warning($e->getMessage(), [
                    'serialId' => $serialId,
                ]);
            }
            if (empty($details['first_air_date'])) {
                $details['first_air_date'] = null;
            }
            if (empty($details['last_air_date'])) {
                $details['last_air_date'] = null;
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
            if (empty($details['in_production'])) {
                $details['adult'] = false;
            }

            $serial = Serial::updateOrCreate([
                'id' => Arr::get($details, 'id'),
            ], Arr::only($details, [
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
            $serial->genres()->sync(Arr::map(Arr::get($details, 'genres'), fn ($genre) => $genre['id']));
            $serial->countries()->sync(
                Country::query()
                    ->whereIn('iso_3166_1', Arr::get($details, 'origin_country', []))
                    ->pluck('id')
            );
        }
    }
}
