<?php

return [
    'settings' => [
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        'logger' => [
            'name' => 'slim-todo',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'db' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1:3306',
            'database' => 'slim_todo',
            'username' => 'homestead',
            'password' => 'secret',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
        'jwt' => [
            'key' => 'XNB',
            'ttl' => 60,
            'refresh_ttl' => 60 * 24 * 7
        ]
    ],
];