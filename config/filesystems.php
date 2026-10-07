<?php

// Only what differs from Laravel's defaults (vendor/laravel/framework/config/filesystems.php)
return [

    'disks' => [

        // storage/app, not storage/app/private: paths are 'public/uploads/...'
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
            'report' => false,
        ],

    ],

];
