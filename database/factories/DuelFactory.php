<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Duel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DuelFactory extends Factory
{
    protected $model = Duel::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->word(),
            'winner_id' => $this->faker->randomNumber(),
            'finished_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
