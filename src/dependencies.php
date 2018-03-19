<?php

use Slim\Container;

$container = $app->getContainer();

/**
 * 日志配置
 *
 * @param Container $c
 * @return \Monolog\Logger
 * @throws Exception
 * @throws \Interop\Container\Exception\ContainerException
 */
$container['logger'] = function (Container $c) {
    $settings = $c->get("settings")['logger'];
    $logger = new Monolog\Logger($settings['name']);
    //$logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

/**
 * 数据库配置
 *
 * @param Container $c
 * @return \Illuminate\Database\Capsule\Manager
 */
$container['db'] = function (Container $c) {
    $capsule = new \Illuminate\Database\Capsule\Manager();
    $capsule->addConnection($c['settings']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
};