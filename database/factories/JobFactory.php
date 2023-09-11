<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\{JobStatus, JobType};
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'name' => Str::kebab('Bot for ' . $this->faker->randomElement([
                'adding application', 'registration', 'buying books',
            ]) . ' ' . $date->format('d-m-y') . ' ' . Str::random(5)),
            'type' => $type = $this->faker->randomElement(JobType::cases()),
            'count_workers' => $this->faker->numberBetween(10, 50),
            'cron' => $type === JobType::Cron ? $this->faker->randomElement([
                '* * * * *',
                '*/5 * * * *',
                '0 0 * * *',
                '0 0 1 * *',
                '0 0 1 1 *',
                '0 0 1 1 1',
            ]) : null,
            'is_loop' => $this->faker->boolean(),
            'pause' => $this->faker->randomNumber(),
            'repetitions' => $this->faker->randomNumber(),
            'status' => $this->faker->randomElement(JobStatus::cases()),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
