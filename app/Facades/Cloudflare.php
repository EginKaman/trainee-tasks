<?php

declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Cloudflare extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-cloudflare';
    }
}
