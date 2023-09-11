<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Bot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BotFactory extends Factory
{
    protected $model = Bot::class;

    public function definition(): array
    {
        return [
            'title' => 'Bot for ' . $this->faker->randomElement(['adding application', 'registration', 'buying books']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
