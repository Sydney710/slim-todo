<?php

namespace App\Https;

use App\Exceptions\TodoException;
use App\Models\Account;
use Firebase\JWT\ExpiredException;
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
        $token = $this->buildToken($req->getUri()->getHost(), $user);

        return $res->withHeader("X-Token", $token)
            ->withJson(["status" => 1, "info" => "OK"]);
    }

    /**
     * 刷新Token
     *
     * @param Request $req
     * @param Response $res
     * @return Response|static
     */
    public function refresh(Request $req, Response $res)
    {
        $token = $req->getHeaderLine('X-Token');
        try {
            $jwt = JWT::decode($token, $this->jwtConf['key'], ['HS256']);
            $account = Account::find(hash2id($jwt["uid"]));
            $token = $this->buildToken($req->getUri()->getHost(), $account);
            return $res->withHeader("X-Token", $token)
                ->withJson(["status" => 1, "info" => "OK"]);
        } catch (ExpiredException $e) {
            $tokenBody = explode('.', $token)[1];
            $tokenBody = JWT::urlsafeB64Decode($tokenBody);
            $jwt = json_decode($tokenBody, true);
            $iat = $jwt["iat"];
            $refreshTtl = $this->jwtConf['refresh_ttl'];
            if ($iat + $refreshTtl < time()) {
                return jsonRes(-1, 'Token已过刷新期' . ($iat + $refreshTtl) . ', ' . time() . print_r($jwt, true));
            }
            $account = Account::find(hash2id($jwt["uid"]));
            $token = $this->buildToken($req->getUri()->getHost(), $account);
            return $res->withHeader("X-Token", $token)
                ->withJson(["status" => 1, "info" => "OK"]);
        } catch (\Exception $e) {
            return jsonRes(0, "Token错误");
        }


    }

    /**
     * 生成Token
     *
     * @param string $host
     * @param Account $account
     * @return string $token
     */
    private function buildToken($host, $account)
    {
        $jwtKey = $this->jwtConf['key'];
        $ttl = $this->jwtConf['ttl'];

        $baseData = [
            "iss" => $host,
            "iat" => time(),
            "exp" => strtotime("+{$ttl} min"),
        ];

        $token = JWT::encode(array_merge($baseData, [
            "uid" => id2hash($account->id),
        ]), $jwtKey);
        return $token;
    }
}