<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->realText(60),
            'description' => $this->faker->realText(100),
            'image' => 'public/subscriptions/' . $this->faker->file(
                storage_path('app/subscriptions'),
                storage_path('app/public/subscriptions'),
                false
            ),
            'price' => 100,
            'period' => '1 month',
        ];
    }
}
