<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use Bezhanov\Faker\Provider\Commerce;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        fake()->addProvider(new Commerce(fake()));
        /** @phpstan-ignore-next-line  */
        $title = fake()->productName();

        return [
            'title:en' => $title,
            'title:de' => $title,
            'description:en' => fake('en_US')->realText(),
            'description:de' => fake('de_DE')->realText(),
            'image' => 'public/products/' . $this->faker->file(
                storage_path('app/products'),
                storage_path('app/public/products'),
                false
            ),
            'quantity' => $this->faker->randomNumber(3, true),
            'price' => $this->faker->numberBetween(50, 999),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
