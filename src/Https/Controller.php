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
     * JSON 数据返回
     *
     * @param int $status
     * @param string $info
     * @param array $playload
     * @return mixed
     */
    protected function jsonRes($status = 1, $info = 'OK', $playload = [])
    {
        $data = [
            "status" => $status,
            "info" => $info,
        ];
        if (!empty($playload)) {
            $data["data"] = $playload;
        }
        return $this->container->response->withJson($data, 200, JSON_UNESCAPED_UNICODE);
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