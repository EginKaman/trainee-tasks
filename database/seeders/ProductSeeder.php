<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $image = 'app/public/products/' . Str::random(40) . '.jpg';
        Storage::copy(storage_path('app/products/Simple cup.jpg'), storage_path($image));
        Product::factory(1)->create([
            'title:en' => 'White mug with a cat',
            'title:de' => 'Weißer Becher mit einer Katze',
            'description:en' => 'Porcelain mug with a diameter of 10 cm is made of sturdy glass',
            'description:de' => 'Porzellanbecher mit einem Durchmesser von 10 cm ist aus stabilem Glas',
            'image' => $image,
        ]);

        $image = 'app/public/products/' . Str::random(40) . '.jpg';
        Storage::copy(storage_path('app/products/T-shirt.jpg'), storage_path($image));
        Product::factory(1)->create([
            'title:en' => 'White t-shirt with a cat',
            'title:de' => 'Weißes T-Shirt mit einer Katze',
            'description:en' => 'The fabric wicks away sweat so that you stay cool',
            'description:de' => 'Der Stoff leitet Schweiß ab, damit du kühl bleibst',
            'image' => $image,
        ]);

        $image = 'app/public/products/' . Str::random(40) . '.jpg';
        Storage::copy(storage_path('app/products/Pen.jpg'), storage_path($image));
        Product::factory(1)->create([
            'title:en' => 'Pen with a cat white',
            'title:de' => 'Stift mit einer Katze weiß',
            'description:en' => 'Features ultra smooth ink flow with a bold tip',
            'description:de' => 'Verfügt über einen ultraglatten Tintenfluss mit einer kräftigen Spitze',
            'image' => $image,
        ]);

        $image = 'app/public/products/' . Str::random(40) . '.jpg';
        Storage::copy(storage_path('app/products/Cup with yellow pattern.jpg'), storage_path($image));
        Product::factory(1)->create([
            'title:en' => 'Yellow mug with pattern',
            'title:de' => 'Gelber Becher mit Muster',
            'description:en' => 'Large handle to comfortably hold the cup',
            'description:de' => 'Großer Griff zum bequemen Halten der Tasse',
            'image' => $image,
        ]);

        $image = 'app/public/products/' . Str::random(40) . '.jpg';
        Storage::copy(storage_path('app/products/Cup with white pattern.jpg'), storage_path($image));
        Product::factory(1)->create([
            'title:en' => 'White mug with pattern',
            'title:de' => 'Weiße Tasse mit Muster',
            'description:en' => 'Beatiful design to match with variety of dishware',
            'description:de' => 'Schönes Design passend zu einer Vielzahl von Geschirr',
            'image' => $image,
        ]);

        Product::factory(1)->create([
            'title:en' => 'Cap with a cat',
            'title:de' => 'Kappe mit einer Katze',
            'description:en' => 'Hook-and-loop back closure for adjustable fit',
            'description:de' => 'Klettverschluss hinten für verstellbare Passform',
            'image' => null,
        ]);
    }
}
