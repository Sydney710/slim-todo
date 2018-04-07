<?php

namespace App\Middleware;

use App\Exceptions\TodoException;
use App\Models\Account;
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
     * 需要验证的path
     *
     * @var array
     */
    private $paths = [];

    /**
     * 允许直接方问路由
     *
     * @var array|mixed
     */
    private $through = [];

    /**
     * JWT key
     *
     * @var string
     */
    private $key = "";

    /**
     * 当前请求Path
     *
     * @var string
     */
    private $path = "";

    private $refresh_path = [];

    public function __construct(ContainerInterface $container, $args = [])
    {
        $this->container = $container;
        $this->paths = array_get($args, 'paths', []);
        $this->through = array_get($args, 'through', []);
        $this->key = $this->container['settings']['jwt']['key'];
        $this->refresh_path = array_get($args, 'refresh', []);
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
        $this->verify($this->path = $req->getUri()->getPath());
        return $next($this->req, $res);
    }

    /**
     * 验证Token
     *
     * @param string $path
     * @return bool|static
     * @throws TodoException
     */
    private function verify($path = '')
    {
        if ($this->shouldThrough($path)) {
            return true;
        }

        if (!$this->shouldVerifyPath($path)) {
            return true;
        }

        return $this->verifyToken();
    }

    /**
     * 是否需要直接访问
     *
     * @param string $path
     * @return bool
     */
    private function shouldThrough($path = '')
    {
        return in_array($path, $this->through);
    }

    /**
     * 验证路由是否需要验证
     *
     * @param string $path
     * @return bool
     */
    private function shouldVerifyPath($path = '')
    {
        foreach ($this->paths as $need) {
            if (strpos($path, $need) != -1) {
                return true;
            }
        }
        return false;
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
        $key = $this->container['settings']['jwt']['key'];
        if (empty($token)) {
            throw new \InvalidArgumentException("Token 不能为空", -1);
        }
        try {
            $jwt = (array)JWT::decode($token, $key, ['HS256']);
        } catch (\Exception $e) {
            throw new TodoException("Token 异常", -1);
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
        if (in_array($this->path, $this->refresh_path)) {
            return true;
        }
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