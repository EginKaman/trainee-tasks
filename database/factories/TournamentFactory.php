<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\TournamentStatus;
use App\Models\Tournament;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\{Carbon, Str};

class TournamentFactory extends Factory
{
    protected $model = Tournament::class;

    public function definition(): array
    {
        return [
            'id' => Str::orderedUuid(),
            'status' => TournamentStatus::Created,
            'title' => $this->faker->unique()->randomElement([
                Str::of($this->faker->colorName())->headline()->title() . ' Tournament',
                Str::of($this->faker->safeColorName())->headline()->title() . ' Tournament',
                Str::of($this->faker->words(3, true))->headline()->title() . ' Tournament',
            ]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function created(CarbonImmutable $date): Factory
    {
        return $this->state([
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
