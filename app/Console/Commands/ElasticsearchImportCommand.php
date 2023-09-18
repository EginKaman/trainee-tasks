<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enum\MediaEnum;
use App\Models\{Movie, Person, Serial};
use App\Services\{ElasticsearchService, MovieDbService};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ElasticsearchImportCommand extends Command
{
    protected $signature = 'elasticsearch:import';

    protected $description = 'Import MovieDB data to Elasticsearch';

    public function __construct(
        public ElasticsearchService $client,
        public MovieDbService $movieDbClient
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->client->deleteIndex('movies_1693929153');
        $this->client->deleteIndex('people_1693929342');
        $this->client->deleteIndex('serials');
        $this->client->deleteIndex('media');
//        foreach (MediaEnum::cases() as $case) {
//            $plural = Str::plural($case->value);
//            $this->client->deleteIndex($plural);
//            $this->client->createIndex($plural);
//
//            $this->withProgressBar($this->json($plural . '.json'), function ($movie) use ($case, $plural): void {
//                $movie = $this->movieDbClient->details($case->value, $movie['id']);
//                if ($case === MediaEnum::Movie && empty($movie['release_date'])) {
//                    $movie['release_date'] = null;
//                }
//                if ($case === MediaEnum::Tv && empty($movie['first_air_date'])) {
//                    $movie['first_air_date'] = null;
//                }
//                if ($case === MediaEnum::Tv && empty($movie['last_air_date'])) {
//                    $movie['last_air_date'] = null;
//                }
//                $this->client->index([
//                    'index' => $plural,
//                    'body' => $movie,
//                ]);
//            });
//        }
    }

    private function json(string $filename): array
    {
        return json_decode(Storage::get($filename), true, 512, JSON_THROW_ON_ERROR);
    }
}
