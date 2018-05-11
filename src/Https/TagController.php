<?php

namespace App\Https;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * 标签管理
 *
 * Class TagController
 * @package App\Https
 */
class TagController extends Controller
{
    /**
     * 标签列表
     *
     * @param Request $req
     * @return Response
     * @throws \App\Exceptions\TodoException
     */
    public function home(Request $req)
    {
        $account = $this->getAccount($req);
        return jsonRes(1, "OK", $account->tags);
    }

    /**
     * 创建标签
     *
     * @param Request $req
     * @return Response
     * @throws \App\Exceptions\TodoException
     */
    public function store(Request $req)
    {
        $account = $this->getAccount($req);
        $name = $req->getParam("name", "");
        if (empty($name)) {
            return jsonRes(0, "标签名称不能为空");
        }
        $isExists = $account->tags()->where("name", $name)->exists();
        if ($isExists) {
            return jsonRes(0, "标签" . $name . "已存在");
        }
        $account->tags()->create([
            "name" => $name,
        ]);
        return jsonRes(1, "标签添加成功");
    }

    /**
     * 更新标签
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     * @throws \App\Exceptions\TodoException
     */
    public function update(Request $req, Response $res, $args = [])
    {
        $account = $this->getAccount($req);
        $name = $req->getParam("name", "");
        if (empty($name)) {
            return jsonRes(0, "标签名称不能为空");
        }
        $tag = $account->tags()->where("id", $args["id"])->first();
        if (empty($tag)) {
            return jsonRes(0, "标签不存在");
        }
        $tag->update(["name" => $name]);
        return jsonRes(1, "标签更新成功");
    }

    /**
     * 删除标签
     *
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     * @throws \App\Exceptions\TodoException
     */
    public function destroy(Request $req, Response $res, $args = [])
    {
        $account = $this->getAccount($req);
        $tag = $account->tags()->where("id", $args["id"])->first();
        if (empty($tag)) {
            return jsonRes(0, "标签不存在");
        }
        $tag->delete();
        return jsonRes(1, "标签删除成功");
    }
}