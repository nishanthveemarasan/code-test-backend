<?php
return [
    'passport' => [
        'client_id' => env('PASSPORT_CLIENT_ID'),
        'client_secret' => env('PASSPORT_CLIENT_SECRET'),
    ],
    'owner' => [
        'email' => env('OWNER_EMAIL'),
        
    ],
    'keys' => [
        'service' => env('APP_SERVICE_KEY'),
    ],
];