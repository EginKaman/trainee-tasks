<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\WorkerStatus;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\{Carbon, Str};

class WorkerFactory extends Factory
{
    protected $model = Worker::class;

    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'name' => Str::kebab('Bot for ' . $this->faker->randomElement([
                'adding application', 'registration', 'buying books',
            ]) . ' ' . $date->format('d-m-y') . ' ' . Str::random(5)),
            'status' => $status = $this->faker->randomElement(WorkerStatus::cases()),
            'completed_at' => ($status === WorkerStatus::Finished) ? Carbon::now() : null,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
