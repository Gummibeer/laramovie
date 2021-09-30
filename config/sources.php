<?php

return [
    'default' => 'emby',

    'providers' => [
        'emby' => [
            'driver' => 'emby',
            'base_url' => env('EMBY_URL'),
            'api_key' => env('EMBY_API_KEY'),
            'server_id' => env('EMBY_SERVER_ID'),
        ],
    ],
];
