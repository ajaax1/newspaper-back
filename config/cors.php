<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://revistaimagemindustrial.com.br',
        'https://www.revistaimagemindustrial.com.br',
        'http://revistaimagemindustrial.com.br',
        'http://www.revistaimagemindustrial.com.br',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Authorization'],

    'max_age' => 0,

    'supports_credentials' => true,
];
