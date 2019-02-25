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

        ]
    ]
];