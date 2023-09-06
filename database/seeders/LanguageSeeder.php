<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Language;
use App\Services\MovieDbService;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $movieDbService = new MovieDbService();

        $languages = $movieDbService->languages();

        foreach ($languages as $language) {
            Language::updateOrCreate([
                'iso_639_1' => $language['iso_639_1'],
            ], [
                'english_name' => $language['english_name'],
                'name' => $language['name'],
            ]);
        }
    }
}
