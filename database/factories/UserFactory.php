<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

//use Intervention\Image\Facades\Image;
//use Xvladqt\Faker\LoremFlickrProvider;

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
//        fake()->addProvider(new LoremFlickrProvider(fake()));
        $gender = $this->faker->randomElement(['male', 'female']);
        $name = $this->faker->firstName($gender) . ' ' . $this->faker->lastName();
//        $image = fake()->image(storage_path('app/public/users'), 400, 400, ['portrait', 'office', $gender]);
//        $photo = Image::make($image);

        return [
            'role_id' => $this->faker->numberBetween(1, 5),
            'name' => $name,
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->e164PhoneNumber(),
            //            'photo_big' => $this->imageBig($photo),
            //            'photo_small' => $this->imageSmall($photo),
            'updated_user_id' => 1,
            'created_user_id' => 1,

            'created_at' => $date = $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween($date, 'now'),
        ];
    }

//    private function imageBig(\Intervention\Image\Image $image): string
//    {
//        $path = 'public/users/' . $image->filename . '.' . $image->extension;
//        $image->resize(70, 70);
//        $image->save(storage_path('app/' . $path));
//
//        return $path;
//    }
//
//    private function imageSmall(\Intervention\Image\Image $image): string
//    {
//        $path = 'public/users/' . $image->filename . '_small.' . $image->extension;
//        $image->resize(50, 50);
//        $image->save(storage_path('app/' . $path));
//
//        return $path;
//    }
}
