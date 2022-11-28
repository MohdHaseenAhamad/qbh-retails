<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CLOUD ENDPOINT
    |--------------------------------------------------------------------------
    |
    */

    'endpoint' => env('LICENCE_ENDPOINT', 'https://rs.findforme.in/api/v1/'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'trial_days' => env('LICENCE_TRIAL_DAYS', 7),

];