<?php

return [


    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://127.0.0.1:8000',
        'http://localhost:8000',
        'http://127.0.0.1',
        'http://localhost',
        // Production domain
        'https://dsimfoniku.com',
        'http://dsimfoniku.com',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
