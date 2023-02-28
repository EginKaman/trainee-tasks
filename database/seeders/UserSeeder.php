<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::factory(44)->create();
        \App\Models\User::factory(1)->create([
            'photo_big' => null,
            'photo_small' => null,
        ]);
    }
}
