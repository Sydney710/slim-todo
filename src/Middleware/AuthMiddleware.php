<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * 验证登录
 *
 * Class AuthMiddleware
 * @package App\Middleware
 */
class AuthMiddleware
{
    public function handle(Request $req, Response $res, callable $next)
    {
        //todo...
        return $next($req, $res);
    }
}