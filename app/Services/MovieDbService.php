<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MovieDbService
{
    protected string $endpoint = 'https://api.themoviedb.org';
    protected int $version = 3;

    public function details(string $type, int $id): array
    {
        return Http::withUrlParameters([
            'endpoint' => $this->endpoint,
            'version' => $this->version,
            'type' => $type,
            'id' => $id,
        ])
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->withToken(config('services.themoviedb.api_key'))
            ->get('{+endpoint}/{version}/{type}/{id}', [
                'language' => 'en-US',
            ])
            ->json();
    }

    public function list(string $type, int $page = 1, bool $includeAdult = false, bool $includeVideo = false): array
    {
        return Http::withUrlParameters([
            'endpoint' => $this->endpoint,
            'version' => $this->version,
            'type' => $type,
        ])
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->withToken(config('services.themoviedb.api_key'))
            ->get('{+endpoint}/{version}/{type}', [
                'language' => 'en-US',
                'include_adult' => $includeAdult,
                'include_video' => $includeVideo,
                'page' => $page,
            ])
            ->json();
    }

    public function countries(): array
    {
        return Http::withUrlParameters([
            'endpoint' => $this->endpoint,
            'version' => $this->version,
            'type' => 'configuration',
            'subType' => 'countries',
        ])
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->withToken(config('services.themoviedb.api_key'))
            ->get('{+endpoint}/{version}/{type}/{subType}', [
                'language' => 'en-US',
            ])
            ->json();
    }
    public function languages(): array
    {
        return Http::withUrlParameters([
            'endpoint' => $this->endpoint,
            'version' => $this->version,
            'type' => 'configuration',
            'subType' => 'languages',
        ])
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->withToken(config('services.themoviedb.api_key'))
            ->get('{+endpoint}/{version}/{type}/{subType}', [
                'language' => 'en-US',
            ])
            ->json();
    }

    public function genresMovieList(): array
    {
        return Http::withUrlParameters([
            'endpoint' => $this->endpoint,
            'version' => $this->version,
            'type' => 'movie',
        ])
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->withToken(config('services.themoviedb.api_key'))
            ->get('{+endpoint}/{version}/genre/{type}/list', [
                'language' => 'en-US',
            ])
            ->json('genres');
    }

    public function genresTvList(): array
    {
        return Http::withUrlParameters([
            'endpoint' => $this->endpoint,
            'version' => $this->version,
            'type' => 'tv',
        ])
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->withToken(config('services.themoviedb.api_key'))
            ->get('{+endpoint}/{version}/genre/{type}/list', [
                'language' => 'en-US',
            ])
            ->json('genres');
    }
}
