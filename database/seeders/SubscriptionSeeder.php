<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        Subscription::factory(1)->create([
            'title:en' => 'Online techno conference subscription',
            'title:de' => 'Abonnement für Online-Techno-Konferenzen',
            'description:en' => 'Subscription includes lectures on IT, robotics, nanotechnology, science, engineering.',
            'description:de' => 'Das Abonnement umfasst Vorlesungen zu IT, Robotik, Nanotechnologie, Wissenschaft und Ingenieurwesen.',
            'stripe_id' => 'price_1MiuvGKgyFZJF7vydXk37usW',
            'paypal_id' => 'P-9EN2354035174592XMQDOLMY',
        ]);
    }
}
