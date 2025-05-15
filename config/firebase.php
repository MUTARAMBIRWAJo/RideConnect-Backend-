<?php

use Kreait\Firebase\ServiceAccount;

return [
    'project_id' => env('FIREBASE_PROJECT_ID'),

    'credentials' => [
        'service_account' => [
            'file' => env('FIREBASE_CREDENTIALS'), // Path to your downloaded JSON key file
            // Or, you can directly paste the JSON content here if you prefer:
            // 'json' => [
            //     'type' => env('FIREBASE_SERVICE_ACCOUNT_TYPE'),
            //     'project_id' => env('FIREBASE_PROJECT_ID'),
            //     // ... other JSON key content
            // ],
        ],
    ],

    'app_check' => [
        'enabled' => env('FIREBASE_APP_CHECK_ENABLED', false),
        'debug_tokens' => explode(',', env('FIREBASE_APP_CHECK_DEBUG_TOKENS', '')),
    ],

    'auth' => [
        'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
        'emulator' => [
            'host' => env('FIREBASE_AUTH_EMULATOR_HOST'),
            'port' => env('FIREBASE_AUTH_EMULATOR_PORT', 9099),
        ],
        'custom_token_leeway_in_seconds' => 60,
    ],

    'database' => [
        'url' => env('FIREBASE_DATABASE_URL'),
        'emulator' => [
            'host' => env('FIREBASE_DATABASE_EMULATOR_HOST'),
            'port' => env('FIREBASE_DATABASE_EMULATOR_PORT', 9000),
        ],
    ],

    'dynamic_links' => [
        'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
    ],

    'messaging' => [
        'emulator' => [
            'host' => env('FIREBASE_MESSAGING_EMULATOR_HOST'),
            'port' => env('FIREBASE_MESSAGING_EMULATOR_PORT', 8080),
        ],
    ],

    'remote_config' => [
        'emulator' => [
            'host' => env('FIREBASE_REMOTE_CONFIG_EMULATOR_HOST'),
            'port' => env('FIREBASE_REMOTE_CONFIG_EMULATOR_PORT', 9001),
        ],
    ],

    'storage' => [
        'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),
        'emulator' => [
            'host' => env('FIREBASE_STORAGE_EMULATOR_HOST'),
            'port' => env('FIREBASE_STORAGE_EMULATOR_PORT', 9199),
        ],
    ],
];
