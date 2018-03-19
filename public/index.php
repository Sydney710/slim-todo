<?php

require __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/../src/settings.php';

$app = new Slim\App($settings);

// 依赖注入
require __DIR__ . '/../src/dependencies.php';
// 系统中间件
require __DIR__ . '/../src/middleware.php';
// 路由配置
require __DIR__ . '/../src/routes.php';

try {
    $app->run();
} catch (\Exception $e) {
    exit(json_encode([
        "status" => 0,
        "info" => $e->getMessage()
    ]));
}