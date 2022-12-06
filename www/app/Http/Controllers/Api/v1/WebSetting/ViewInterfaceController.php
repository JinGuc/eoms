<?php

namespace App\Http\Controllers\Api\v1\WebSetting;

use App\Http\Controllers\Controller;
use App\Models\UrlInfo;
use App\Models\UrlStatusInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// 外部接口监控
class ViewInterfaceController extends Controller
{
    /**
     * 接口监控列表
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        Log::info("获取接口监控列表信息", ["request" => $request]);
        $page = $request->input('page', 1);
        $limit = $request->input('pagesize', 20);
        $title = $request->input('title', '');

        $query = UrlInfo::query();

        if($title)
        {
            $query->where("title", 'like', "%{$title}%");
        }
        if($request->input('status')) 
        {
            $query->where("status", $request->input('status'));
        }

        $query->orderBy("id", 'ASC');
        $total = $query->count();

        $UrlInfoList = $query->offset(($page - 1) * $limit)
            ->limit($limit);

        if($UrlInfoList->exists())
        {
            $UrlInfoList = $UrlInfoList->get()->toArray();
            $resList = array(
                'data' => $UrlInfoList,
                'total' => $total

            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }

    /**
     * 接口监控添加或修改
     * @param Request $request
     * @return array|string[]
     */
    public function addOrEdit(Request $request)
    {
        Log::info("添加或修改接口监控信息", ["request" => $request]);
        $act = $request->input('act','');//当前动作，表示想做另外的事
        $id = $request->input('id','');//传了为编辑，未传为新增
        $UrlInfoObj = $id ? UrlInfo::find($id) : new UrlInfo;
        if($id && empty($UrlInfoObj)){
            return ["status" => "fail", "des" => '未知的对象', "res"=>[]];
        }
        if($act == 'changeStatus'){//该动作表示只更新状态字段
            if(!$id){
                return ["status" => "fail", "des" => '无效的id'];
            }
            $UrlInfoObj->status = $request->input('status',UrlInfo::$_status['on']['code']);

        }else{
            $UrlInfoObj->title = $request->input('title');
            $UrlInfoObj->url = $request->input('url');
            $UrlInfoObj->port = $request->input('port');
            $UrlInfoObj->type = strtolower($request->input('type'));
            $UrlInfoObj->remark = $request->input('remark');
            $UrlInfoObj->timeout = $request->input('timeout');
            $UrlInfoObj->status = $request->input('status',UrlInfo::$_status['on']['code']);
            if(strtolower($request->input('type')) == 'mysql')
            {
                $UrlInfoObj->db_username = $request->input('db_username');
                $UrlInfoObj->db_password = $request->input('db_password');
            }
        }
        if($UrlInfoObj->save())
        {
            $resList = [
                'data' => [
                    'id' => $UrlInfoObj->id
                ]
            ];
            return ["status" => "success", "des" => '保存成功', "res" => $resList];
        }
        return ["status" => "fail", "des" => '保存失败', 'res'=>[]];
    }

    /**
     * 接口监控删除
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        Log::info("接口监控信息删除", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $UrlInfoList = UrlInfo::find($request->input('id'));
        if($UrlInfoList->delete()){
            return ["status" => "success", "des" => '删除成功', 'res'=>[]];
        }
        return ["status" => "fail", "des" => '删除失败', 'res'=>[]];
    }

    /**
     * 接口监控 详情
     * @param Request $request
     * @return array
     */
    public function detail(Request $request)
    {
        Log::info("接口监控信息详情", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $UrlInfoList = UrlInfo::find($request->input('id'));
        if($UrlInfoList){
            return ["status" => "success", "des" => '成功', 'res'=>$UrlInfoList->toArray()];
        }
        return ["status" => "fail", "des" => '无数据', 'res'=>[]];
    }

    /**
     * 接口状态监控列表
     * @param Request $request
     * @return array
     */
    public function urlstatusList(Request $request)
    {
        Log::info("获取接口状态监控列表信息", ["request" => $request]);
        $page = $request->input('page', 1);
        $limit = $request->input('pagesize', 20);
        $title = $request->input('title', '');

        $query = UrlInfo::query();

        if($title)
        {
            $query->where("title", 'like', "%{$title}%");
        }
        $query->where("status", UrlInfo::$_status['on']['code']);

        $total = $query->count();
        $query->orderBy("id", 'ASC');
        $UrlInfoList = $query->offset(($page - 1) * $limit)
            ->limit($limit);

        if($UrlInfoList->exists())
        {
            $UrlInfoList = $UrlInfoList->get()->toArray();
            $resList = array(
                'data' => $UrlInfoList,
                'total' => $total

            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }
}
