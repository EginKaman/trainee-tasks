<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Country;
use App\Services\MovieDbService;
use Illuminate\Database\Seeder;
use Throwable;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        try {
            $countriesData = (new MovieDbService())->countries();
        } catch (Throwable $e) {
            $this->command->error($e->getMessage());

            return;
        }

        foreach ($countriesData as $countryData) {
            Country::updateOrCreate([
                'iso_3166_1' => $countryData['iso_3166_1'],
            ], [
                'english_name' => $countryData['english_name'],
                'native_name' => $countryData['native_name'],
            ]);
        }
    }
}
