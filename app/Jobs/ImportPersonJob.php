<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enum\MediaEnum;
use App\Models\Person;
use App\Services\MovieDbService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Arr;

class ImportPersonJob implements ShouldQueue
{
    use Dispatchable; use InteractsWithQueue; use Queueable; use SerializesModels;

    private MovieDbService $movieDbClient;

    public function __construct(
        public array $personIds
    )
    {
        $this->movieDbClient = new MovieDbService();
    }

    public function handle(): void
    {
        $type = MediaEnum::Person;
        foreach ($this->personIds as $personId) {
            $personDetails = $this->movieDbClient->details($type->value, $personId['id']);
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
        }
    }
}
