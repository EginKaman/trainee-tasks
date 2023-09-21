<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TournamentFactory extends Factory
{
    protected $model = Tournament::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->word(),
            'finished_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
