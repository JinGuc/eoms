<?php

namespace App\Http\Controllers\Api\v1\WebSetting;

use App\Http\Controllers\Controller;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebSettingController extends Controller
{
    /**
     * 系统参数列表
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        Log::info("获取系统参数列表信息", ["request" => $request]);
        $page = $request->input('page', 1);
        $limit = $request->input('pagesize', 20);
        $keyword = $request->input('keyword', '');

        $query = WebSetting::query();

        if($keyword)
        {
            $query->where(function ($query) use($keyword) {
                $query->where("fd_name", 'like', "%$keyword%")
                    ->orWhere('note', 'like', "%{$keyword}%");
            });
        }
        if($request->input('status')) $query->where("status", $request->input('status'));

        $query->orderBy("id", 'ASC');
        $total = $query->count();


        $WebSettingList = $query->offset(($page - 1) * $limit)
            ->limit($limit);


        if($WebSettingList->exists())
        {
            $WebSettingList = $WebSettingList->get()
            ->toArray();
            $resList = array(
                'data' => $WebSettingList,
                'total' => $total

            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }

    /**
     * 系统参数添加或修改
     * @param Request $request
     * @return array|string[]
     */
    public function addOrEdit(Request $request)
    {
        Log::info("添加或修改系统参数信息", ["request" => $request]);
        $act = $request->input('act','');//当前动作，表示想做另外的事
        $id = $request->input('id','');//传了为编辑，未传为新增
        $WebSettingObj = $id ? WebSetting::find($id) : new WebSetting;
        if($id && empty($WebSettingObj)){
            return ["status" => "fail", "des" => '未知的对象', "res"=>[]];
        }
        if($act == 'changeStatus'){//该动作表示只更新状态字段
            if(!$id){
                return ["status" => "fail", "des" => '无效的id'];
            }
            $WebSettingObj->status = $request->input('status',WebSetting::$_status['on']['code']);

        }else{
            $WebSettingObj->value = $request->input('value');
            $WebSettingObj->status = $request->input('status',WebSetting::$_status['on']['code']);
        }
        if($WebSettingObj->save())
        {
            $resList = [
                'data' => [
                    'id' => $WebSettingObj->id
                ]
            ];
            return ["status" => "success", "des" => '保存成功', "res" => $resList];
        }
        return ["status" => "fail", "des" => '保存失败，存在相同数据', 'res'=>[]];
    }

    /**
     * 系统参数删除
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        Log::info("系统参数信息删除", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $WebSettingList = WebSetting::find($request->input('id'));
        if($WebSettingList->delete()){
            return ["status" => "success", "des" => '删除成功', 'res'=>[]];
        }
        return ["status" => "fail", "des" => '删除失败', 'res'=>[]];
    }

    /**
     * 启用的系统参数列表
     * @param Request $request
     * @return array
     */
    public function EnableList(Request $request)
    {
        Log::info("获取启用的系统参数列表信息", ["request" => $request]);

        $query = WebSetting::query();

        $query->orderBy("id", 'ASC');
        $query->where('type', 1);
        $query->where('status',1);



        if($query->exists())
        {
            $WebSettingList = $query->get(['fd_name','value'])
                ->toArray();
            $data = [];
            foreach ($WebSettingList as $value) {
                $data[$value["fd_name"]] = $value["value"];
            }
            $resList = array(
                'data' => $data

            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }
}
