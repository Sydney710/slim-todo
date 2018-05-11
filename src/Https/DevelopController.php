<?php

namespace App\Https;

use App\Models\Account;
use Slim\Http\Request;

class DevelopController extends Controller
{
    public function init()
    {
        Account::create([
            "username" => "Sugar",
            "email" => "hxtgirq710@qq.com",
            "password" => password_hash("123456", PASSWORD_BCRYPT),
            "nickname" => "冰糖",
            "lock" => "F",
        ]);
        return "OK";
    }

    public function test(Request $req)
    {
        $account = $req->getAttribute("jwt");
        return jsonRes(1, "OK", $account);
    }
}