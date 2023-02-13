<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\FileHelperService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('FileHelper', fn (Application $app) => new FileHelperService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
