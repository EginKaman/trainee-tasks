<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Kraken;

class KrakenServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            'Kraken',
            fn (Application $app) => new Kraken(config('services.kraken.key'), config('services.kraken.secret'))
        );
    }
}
