<?php

use App\Https\AuthController;
use App\Https\ProjectController;
use App\Https\TodoController;
use App\Https\TagController;
use App\Middleware\PermissionMiddleware as Permission;

$app->group("/v1.0", function () use ($app) {

    $app->get("/init", \App\Https\DevelopController::class . ':init');
    $app->get("/test", \App\Https\DevelopController::class . ':test');

    $app->post("/auth/token", AuthController::class . ":auth");
    $app->post("/auth/refresh", AuthController::class . ':refresh');

    $app->group("", function () use ($app) {
        // 项目管理
        $app->get("/project", ProjectController::class . ':home');
        $app->post("/project", ProjectController::class . ':store');
        $app->put("/project/{id}", ProjectController::class . ':update');
        $app->delete("/project/{id}", ProjectController::class . ':destroy');

        // 作务管理
        $app->get("/todo", TodoController::class . ':home');
        $app->post("/todo", TodoController::class . ':store');
        $app->put("/todo/{id}", TodoController::class . ':update');
        $app->put("/todo/{id}/resolve", TodoController::class . ':resolve');
        $app->delete("/todo/{id}", TodoController::class . ':destroy');

        // 标签管理
        $app->get("/tag", TagController::class . ':home');
        $app->post("/tag", TagController::class . ":store");
        $app->put("/tag/{id}", TagController::class . ':update');
        $app->delete("/tag/{id}", TagController::class . ':destroy');
    })->add(Permission::class);


});