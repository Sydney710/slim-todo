<?php

namespace App\Https;

use App\Exceptions\TodoException;
use App\Models\Account;
use Firebase\JWT\JWT;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * 认证
 *
 * Class AuthController
 * @package App\Https
 */
class AuthController extends Controller
{
    private $jwtConf = [];

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->jwtConf = $container->settings['jwt'];
    }

    /**
     * 授权获取Token
     *
     * @param Request $req
     * @param Response $res
     * @return Response
     * @throws TodoException
     */
    public function auth(Request $req, Response $res)
    {
        $email = $req->getParam("email", "");
        $password = $req->getParam("password", "");

        $user = Account::where("email", $email)->first();
        if (empty($user) || !password_verify($password, $user->password)) {
            throw new TodoException("邮箱或密码错误");
        }
        if ($user->is_lock == "T") {
            throw new TodoException("账号已锁定");
        }
        list($token, $refresh) = $this->buildToken($req->getUri()->getHost(), $user);

        return $res->withHeader("X-Token", $token)
            ->withHeader("X-Refresh-Token", $refresh)
            ->withJson(["status" => 1, "info" => "OK"]);
    }

    /**
     * 刷新Token
     *
     * @param Request $req
     */
    public function refresh(Request $req)
    {

    }

    /**
     * 生成Token
     *
     * @param $host
     * @param $account
     * @return array
     */
    private function buildToken($host, $account)
    {
        $jwtKey = $this->jwtConf['key'];
        $ttl = $this->jwtConf['ttl'];
        $refreshTtl = $this->jwtConf['ttl_refresh'];

        $baseData = [
            "iss" => $host,
            "iat" => time(),
            "exp" => strtotime("+{$ttl} min"),
        ];

        $token = JWT::encode(array_merge($baseData, [
            "uid" => id2hash($account->id),
        ]), $jwtKey);

        $refresh = JWT::encode(array_merge([
            "exp" => strtotime("+{$refreshTtl} days")
        ], ["token" => $token]), $jwtKey);
        return [$token, $refresh];
    }
}