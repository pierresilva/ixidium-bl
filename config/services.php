<?php

return [

    /*
    |--------------------------------------------------------------------------
    | MigrThird Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('BASELINE_MAILGUN_DOMAIN'),
        'secret' => env('BASELINE_MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('BASELINE_SES_KEY'),
        'secret' => env('BASELINE_SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('BASELINE_SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('BASELINE_STRIPE_KEY'),
        'secret' => env('BASELINE_STRIPE_SECRET'),
    ],

];
