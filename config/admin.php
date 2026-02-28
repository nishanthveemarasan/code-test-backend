<?php
return [
    'passport' => [
        'client_id' => env('PASSPORT_CLIENT_ID'),
        'client_secret' => env('PASSPORT_CLIENT_SECRET'),
    ],
    'owner' => [
        'email' => env('OWNER_EMAIL'),
        'id' => env('APP_USER'),
        
    ],
    'keys' => [
        'service' => env('APP_SERVICE_KEY'),
    ],
    'dropbox' => [
        'store_images' => [
            'code' => 'store_images',
            'client_id' => env('DROPBOX_APP_KEY',''),
            'client_secret' => env('DROPBOX_APP_SECRET',''),
            'app_redirect_url' => env('DROPBOX_APP_REDIRECT_URL',''),
            'mode' => env('APP_ENV',''),
        ]
    ]
];