<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Genre;
use App\Services\MovieDbService;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $movieDbService = new MovieDbService();

        $genresMovieList = $movieDbService->genresMovieList();

        foreach ($genresMovieList as $genre) {
            Genre::updateOrCreate([
                'id' => $genre['id'],
            ], [
                'name' => $genre['name'],
            ]);
        }

        $genresTvList = $movieDbService->genresTvList();

        foreach ($genresTvList as $genre) {
            Genre::updateOrCreate([
                'id' => $genre['id'],
            ], [
                'name' => $genre['name'],
            ]);
        }
    }
}
