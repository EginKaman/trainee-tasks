<?php

declare(strict_types=1);

namespace App\Services;

use Elastic\Elasticsearch\{Client, ClientBuilder};

class ElasticsearchService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([config('elasticsearch.host')])
            ->build();
    }

    public function index(array $params): void
    {
        $this->client->index($params);
    }

    public function createIndex(string $name): void
    {
        $this->client->indices()->create([
            'index' => $name,
            'body' => [
                'settings' => [
                    'index' => [
                        'mapping' => [
                            'ignore_malformed' => true,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function deleteIndex(string $name): void
    {
        $this->client->indices()->delete([
            'index' => $name,
        ]);
    }

    public function search(string $index, array $body): array
    {
        return $this->client->search([
            'indices_boost' => config('elasticsearch.indices_boost'),
            'index' => $index,
            'body' => $body,
        ])->asArray();
    }
}
