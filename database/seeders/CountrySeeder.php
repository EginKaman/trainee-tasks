<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        try {
            $countriesData = json_decode(Storage::get('countries.json'), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->command->error($e->getMessage());

            return;
        }

        foreach ($countriesData as $countryData) {
            Country::updateOrCreate([
                'iso_2_code' => $countryData['alpha-2'],
            ], [
                'title' => $countryData['name'],
            ]);
        }
    }
}
