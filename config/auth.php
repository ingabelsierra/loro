<?php

return [    

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
            'hash' => false,
        ],

        'ApiMind' => [
            'driver' => 'eloquent',
            'provider' => 'users',
        ],

    ],

    
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],
       
    ],
    
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

];
