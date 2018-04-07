<?php

$app->add(new \App\Middleware\JwtMiddleware($app->getContainer(), [
    'paths' => ['/v1.0'],
    'through' => ['/v1.0/init', '/v1.0/auth/token'],
    'refresh' => ["/v1.0/auth/refresh"],
]));