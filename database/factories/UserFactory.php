<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Xvladqt\Faker\LoremFlickrProvider;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        fake()->addProvider(new LoremFlickrProvider(fake()));

        return [
            'name' => fake()->firstName() . ' ' . fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake('uk_UA')->e164PhoneNumber(),
            'photo_small' => 'public/users/' . fake()->image(
                storage_path('app/public/users'),
                30,
                30,
                ['man', 'woman'],
                false,
                false
            ),
            'photo_big' => 'public/users/' . fake()->image(
                storage_path('app/public/users'),
                70,
                70,
                ['man', 'woman'],
                false,
                false
            ),
            'updated_user_id' => 1,
            'created_used_id' => 1,
        ];
    }
}
