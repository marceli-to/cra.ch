<?php

use Illuminate\Support\Facades\Facade;

// Only what differs from Laravel's defaults (vendor/laravel/framework/config/app.php)
return [

    'locale' => env('APP_LOCALE', 'de'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'de'),

    'aliases' => Facade::defaultAliases()->merge([
        'AppHelper' => App\Helpers\AppHelper::class,
        'DateHelper' => App\Helpers\DateHelper::class,
    ])->toArray(),

];
