<?php

namespace App\Https;

use App\Exceptions\TodoException;
use Slim\Container;
use Slim\Http\Request;

class Controller
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $req
     * @return mixed
     * @throws TodoException
     */
    public function getAccount(Request $req)
    {
        $account = $req->getAttribute("jwt");
        if (empty($account)) {
            throw new TodoException("请先登录");
        }
        return $account;
    }
}