<?php

namespace App\Http\Controllers\Api\v1\Snmp;

use App\Http\Controllers\Controller;
use App\Models\SnmpOid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SnmpOidController extends Controller
{

    public function list(Request $request)
    {
        Log::info("获取 snmp oid 列表信息", ["request" => $request]);
        $page = $request->input('page', 1);
        $limit = $request->input('pagesize', 20);

        $query = SnmpOid::query();

        $query->orderBy("created_at", 'DESC');
        $total = $query->count();


        $SnmpOidList = $query->offset(($page - 1) * $limit)
            ->limit($limit);


        if($SnmpOidList->exists())
        {
            $SnmpOidList = $SnmpOidList->get()
                ->toArray();
            $resList = array(
                'data' => $SnmpOidList,
                'total' => $total

            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }

    public function addOrEdit(Request $request)
    {
        Log::info("添加或修改snmp oid 信息", ["request" => $request]);
        $act = $request->input('act','');//传了为编辑，未传为新增
        $id = $request->input('id','');//传了为编辑，未传为新增
        $SnmpOidObj = $id ? SnmpOid::find($id) : new SnmpOid;
        if($id && empty($SnmpOidObj)){
            return ["status" => "fail", "des" => '未知的对象', "res"=>[]];
        }
        if($act == 'changeStatus'){//该动作表示只更新状态字段
            if(!$id){
                return ["status" => "fail", "des" => '无效的id'];
            }
            $SnmpOidObj->status = $request->input('status',SnmpOid::$_status['on']['code']);

        }
        else {
            $validate = Validator::make($request->all(), [
                "serverName" => "required",
                "serverType" => "required",
                "oid" => "required",
            ], [
                "serverName.required" => "名称不能为空",
                "serverType.required" => "监控名称不能为空",
                "oid.required" => "oid不能为空",
            ]);
            if ($validate->fails()) {
                return ["status" => "fail", "des" => $validate->errors()->first(), 'res' => []];
            }
            $SnmpOidExists = SnmpOid::where('oid', $request->input('oid'))
                ->when($id, function ($query, $id) {
                    return $query->where('id', '!=', $id);
                });
            if ($SnmpOidExists->exists()) {
                return ["status" => "fail", "des" => 'oid不能重复', 'res' => []];
            }
            $SnmpOidObj->serverName = $request->input('serverName');
            $SnmpOidObj->serverType = $request->input('serverType');
            $SnmpOidObj->oid = $request->input('oid');
            $SnmpOidObj->status = $request->input('status');
            $SnmpOidObj->type = $request->input('type');
        }
        if ($SnmpOidObj->save()) {
            $resList = [
                'data' => [
                    'id' => $SnmpOidObj->id
                ]
            ];
            return ["status" => "success", "des" => '保存成功', "res" => $resList];
        }
        return ["status" => "fail", "des" => '保存失败，存在相同数据', 'res' => []];
    }

    public function info(Request $request)
    {
        Log::info("oid信息详情获取", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $ContactObj = SnmpOid::find($request->input('id'));
        if($ContactObj){
            $ContactObj = $ContactObj->toArray();
            return ["status" => "success", "des" => '获取成功', 'res'=>["data"=>$ContactObj]];
        }
        return ["status" => "fail", "des" => '获取失败', 'res'=>[]];
    }

    public function delete(Request $request)
    {
        Log::info("snmp oid 信息删除", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $SnmpHostObj = SnmpOid::find($request->input('id'));
        if($SnmpHostObj->delete()){
            return ["status" => "success", "des" => '删除成功', 'res'=>[]];
        }
        return ["status" => "fail", "des" => '删除失败', 'res'=>[]];
    }
}
