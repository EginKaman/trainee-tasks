<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Intervention\Image\Facades\Image;
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
        $image = fake()->image(storage_path('app/public/users'), 400, 400, ['portrait']);
        $photo = Image::make($image);

        return [
            'role_id' => fake()->numberBetween(1, Role::count()),
            'name' => fake()->firstName() . ' ' . fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake('uk_UA')->e164PhoneNumber(),
            'photo_big' => $this->imageBig($photo),
            'photo_small' => $this->imageSmall($photo),
            'updated_user_id' => 1,
            'created_user_id' => 1,
        ];
    }

    private function imageBig(\Intervention\Image\Image $image): string
    {
        $path = 'public/users/' . $image->filename . '.' . $image->extension;
        $image->resize(70, 70);
        $image->save(storage_path('app/' . $path));

        return $path;
    }

    private function imageSmall(\Intervention\Image\Image $image): string
    {
        $path = 'public/users/' . $image->filename . '_small.' . $image->extension;
        $image->resize(38, 38);
        $image->save(storage_path('app/' . $path));

        return $path;
    }
}
