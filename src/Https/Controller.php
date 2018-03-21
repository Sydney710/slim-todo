<?php

namespace App\Https;

use Slim\Container;

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

}