<?php

declare(strict_types=1);

return [
    'email' => env('CLOUDFLARE_EMAIL'),

    'key' => env('CLOUDFLARE_API_KEY'),

    'zone_id' => env('CLOUDFLARE_ZONE_ID'),
];
