<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Cloudflare;
use Illuminate\Support\ServiceProvider;

class CloudflareServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     */
    protected bool $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../..//config/cloudflare.php' => config_path('cloudflare.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/cloudflare.php', 'cloudflare');

        $cloudflareConfig = config('cloudflare');

        $this->app->bind(
            Cloudflare::class,
            fn () => new Cloudflare($cloudflareConfig['email'], $cloudflareConfig['key'])
        );

        $this->app->alias(Cloudflare::class, 'laravel-cloudflare');
    }
}
