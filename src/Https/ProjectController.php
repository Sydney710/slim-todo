<?php

namespace App\Https;

use App\Models\Project;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * 项目管理
 *
 * Class ProjectController
 * @package App\Https
 */
class ProjectController extends Controller
{

    /**
     * 项目列表
     *
     * @param Request $req
     * @return Response
     * @throws \App\Exceptions\TodoException
     */
    public function home(Request $req)
    {
        $account = $this->getAccount($req);
        $list = Project::where("account_id", $account->id)->get();
        return jsonRes(1, "OK", $list);
    }

    /**
     * 创建项目
     *
     * @param Request $req
     * @return Response
     * @throws \App\Exceptions\TodoException
     */
    public function store(Request $req)
    {
        $account = $this->getAccount($req);
        $name = $req->getParam('name');
        if (empty($name)) {
            return jsonRes(0, '项目名称不能为空');
        }
        Project::create([
            "name" => $name,
            "account_id" => $account->id,
            "sort" => 500,
        ]);
        return jsonRes(1, "项目" . $name . "创建成功");
    }

    /**
     * 更新项目
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
        $project_id = $args['id'];
        $name = $req->getParam("name", "");
        if (empty($project_id) || empty($name)) {
            return jsonRes(0, "参数错误", $args);
        }
        Project::where("account_id", $account->id)->where("id", $project_id)->update([
            'name' => $name,
        ]);
        return jsonRes(1, '项目更新成功');
    }

    /**
     * 删除项目
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
        $project_id = $args['id'];
        $project = Project::find($project_id);
        if (empty($project) || $project->account_id != $account->id) {
            return jsonRes(0, '非法操作');
        }
        $project->delete();
        return jsonRes(1, '项目' . $project->name . '删除成功');
    }


}