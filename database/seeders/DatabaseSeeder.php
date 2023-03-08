<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{Role, Subscription};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::factory(5)->create();
        Subscription::factory(1)->create([
            'title:en' => 'Online techno conference subscription',
            'title:de' => 'Abonnement für Online-Techno-Konferenzen',
            'description:en' => 'Subscription includes lectures on IT, robotics, nanotechnology, science, engineering.',
            'description:de' => 'Das Abonnement umfasst Vorlesungen zu IT, Robotik, Nanotechnologie, Wissenschaft und Ingenieurwesen.',
            'stripe_id' => 'price_1MiuvGKgyFZJF7vydXk37usW',
            'paypal_id' => 'P-9EN2354035174592XMQDOLMY',
        ]);
        $this->call([ProductSeeder::class, UserSeeder::class]);
    }
}
