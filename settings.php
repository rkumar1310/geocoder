<?php

declare(strict_types=1);

define('APP_ROOT', __DIR__);

// settings for development

return [
    'settings' => [
        'slim' => [
            'displayErrorDetails' => true,
            'logErrors' => true,
            'logErrorDetails' => true,
        ],

        'doctrine' => [
            'dev_mode' => true,
            'cache_dir' => APP_ROOT . '/var/doctrine',
            'metadata_dirs' => [APP_ROOT . '/app/Domain'],
            'connection' => [
                'driver' => 'pdo_pgsql',
                'host' => getenv("DB_HOST"),
                'port' => getenv("DB_PORT"),
                'dbname' => getenv("DB_NAME"),
                'user' => getenv("DB_USER"),
                'password' => getenv("DB_PASSWORD"),
                'charset' => 'utf-8'
            ]
        ]
    ]
];