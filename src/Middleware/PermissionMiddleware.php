<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * 验证权限
 *
 * Class PermissionMiddleware
 * @package App\Middleware
 */
class PermissionMiddleware
{
    public function handle(Request $req, Response $res, callable $next)
    {
        //todo...
        return $next($req, $res);
    }
}