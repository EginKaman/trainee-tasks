<?php

declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kraken
 */
class KrakenFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Kraken';
    }
}
