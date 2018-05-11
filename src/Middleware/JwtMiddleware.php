<?php

namespace App\Middleware;

use App\Exceptions\TodoException;
use App\Models\Account;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * JWT 验证中间件
 *
 * Class AuthMiddleware
 * @property Logger $logger
 * @package App\Middleware
 */
class JwtMiddleware
{
    private $container;

    /** @var Request $req */
    private $req;

    /**
     * JWT key
     *
     * @var string
     */
    private $key = "";

    public function __construct(ContainerInterface $container, $args = [])
    {
        $this->container = $container;
        $this->key = $this->container['settings']['jwt']['key'];
    }

    public function __get($name)
    {
        return $this->container->get($name);
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param callable $next
     * @return mixed
     * @throws TodoException
     */
    public function __invoke(Request $req, Response $res, callable $next)
    {
        $this->req = $req;
        $this->verify();
        $res = $next($this->req, $res);
        return $res;
    }

    /**
     * 验证Token
     *
     * @return bool|static
     * @throws TodoException
     */
    private function verify()
    {
        return $this->verifyToken();
    }

    /**
     * 获取Token值
     *
     * @return array
     * @throws TodoException
     */
    private function getJwt()
    {
        $token = $this->req->getHeaderLine("X-Token");

        if (empty($token)) {
            throw new \InvalidArgumentException("Token 不能为空", -1);
        }
        try {
            $jwt = (array)JWT::decode($token, $this->key, ['HS256']);
        } catch (\Exception $e) {
            throw new TodoException($e->getMessage(), -1);
        }
        return $jwt;
    }

    /**
     * @return bool
     * @throws TodoException
     */
    private function verifyToken()
    {
        $jwt = $this->getJwt();
        $account = $this->getAccount($jwt["uid"]);
        $this->req = $this->req->withAttribute("jwt", $account);
        return true;
    }

    /**
     * @param $uid
     * @return mixed
     * @throws TodoException
     */
    private function getAccount($uid)
    {
        if (empty($uid)) {
            throw new TodoException("Token 错误", -1);
        }
        $account = Account::find(hash2id($uid));
        if (empty($account)) {
            throw new TodoException("Token 错误", -1);
        }
        if ($account->is_lock == 'T') {
            throw new TodoException("账号已锁定", 0);
        }
        return $account;
    }

}