<?php

return [
    'default' => 'google',

    'connections' => [
        'google' => [
            'key' => env('GOOGLE_API_KEY'),
        ],

        'yelp' => [
            'key' => env('YELP_API_KEY'),
        ],

        'here' => [
            'app_id' => env('HERE_APP_ID'),
            'key' => env('HERE_APP_CODE'),
        ],
    ]
];