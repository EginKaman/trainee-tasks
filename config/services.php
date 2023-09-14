<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY'),
        'webhook' => [
            'verification_key' => env('SENDGRID_VERIFICATION_KEY'),
        ],
        'templates' => [
            'dynamic_template_id' => env('SENDGRID_DYNAMIC_TEMPLATE_ID'),
        ],
    ],

    'kraken' => [
        'key' => env('KRAKEN_API_KEY'),
        'secret' => env('KRAKEN_API_SECRET'),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'stripe' => [
        'api_key' => env('STRIPE_KEY'),
        'api_secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
    'themoviedb' => [
        'api_key' => env('MOVIE_DB_API_KEY'),
    ],
    'turbosms' => [
        'wsdlEndpoint' => env('TURBOSMS_WSDLENDPOINT', 'http://turbosms.in.ua/api/wsdl.html'),
        'login' => env('TURBOSMS_LOGIN'),
        'password' => env('TURBOSMS_PASSWORD'),
        'sender' => env('TURBOSMS_SENDER'),
        'debug' => env('TURBOSMS_DEBUG', false), //will log sending attempts and results
        'sandboxMode' => env('TURBOSMS_SANDBOX_MODE', false), //will not invoke API call
    ],
];
