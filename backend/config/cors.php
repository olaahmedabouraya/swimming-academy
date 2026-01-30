<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:4200',
        env('FRONTEND_URL', 'https://swimming-academy-git-main-ola-ahmed-abou-rayas-projects.vercel.app'),
    ],

    'allowed_origins_patterns' => [
        '#^https://swimming-academy.*\.vercel\.app$#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];


