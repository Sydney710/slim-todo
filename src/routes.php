<?php

use App\Https\AuthController;
use App\Https\ProjectController;
use App\Https\TodoController;
use App\Https\TagController;
use App\Middleware\AuthMiddleware as Auth;
use App\Middleware\PermissionMiddleware as Permission;

$app->group("/v1.0", function () use ($app) {

    $app->get("/test", function(){
        $this->logger->info("ABC");
       return json_encode([
           "status" => 1,
           "info" => "OK",
           "data" => [
               "word" => "hello world",
           ]
       ]);
    });
    $app->post("/auth", AuthController::class . ":auth");
    $app->post("/auth/refresh", AuthController::class . ':refresh');

    $app->group("", function () use ($app) {
        // 项目管理
        $app->get("/project", ProjectController::class . ':home');
        $app->post("/project", ProjectController::class . ':store');
        $app->put("/project/{id}", ProjectController::class . ':update');

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
    })->add(Auth::class)->add(Permission::class);


});