<?php

// Only what differs from Laravel's defaults (vendor/laravel/framework/config/auth.php)
return [

    'passwords' => [
        'users' => [
            'provider' => 'users',
            // Created before Laravel 10 renamed it to password_reset_tokens
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

];
