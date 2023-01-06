<?php

namespace App\Http\Controllers\Api\v1\HostRole;

use App\Http\Controllers\Controller;
use App\Models\SnmpRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HostRoleController extends Controller
{
    /**
     * 服务器可选角色列表
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $roles = SnmpRole::all()->toArray();
        $data = [];
        foreach ($roles as $role)
        {
            $data[$role["key"]] = $role["name"];
        }
        if(count($data)>0)
        {
            return ["status"=>"success", "des"=>"获取成功","res"=>$data];
        }
        return ["status"=>"fail", "des"=>"未设置角色","res"=>[]];
    }

    public function index(Request $request)
    {
        Log::info("获取服务器角色列表信息", ["request" => $request]);
        $page = $request->input('page', 1);
        $limit = $request->input('pagesize', 20);

        $query = SnmpRole::query();

        $query->orderBy("created_at", 'DESC');
        $total = $query->count();


        $RoleList = $query->offset(($page - 1) * $limit)
            ->limit($limit);


        if($RoleList->exists())
        {
            $RoleList = $RoleList->get()
                ->toArray();
            $resList = array(
                'data' => $RoleList,
                'total' => $total

            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }

    public function addOrEdit(Request $request)
    {
        Log::info("添加或修改服务器角色信息", ["request" => $request]);
        $id = $request->input('id','');//传了为编辑，未传为新增
        $SnmpRoleObj = $id ? SnmpRole::find($id) : new SnmpRole;
        if($id && empty($SnmpRoleObj)){
            return ["status" => "fail", "des" => '未知的对象', "res"=>[]];
        }
        $validate = Validator::make($request->all(),[
            "name"=>"required",
            "key"=>"required",
        ],[
            "name.required"=>"角色名称不能为空",
            "key.required"=>"角色标识不能为空",
        ]);
        if($validate->fails()){
            return ["status" => "fail", "des" => $validate->errors()->first(), 'res'=>[]];
        }
        $SnmpRoleExists = SnmpRole::where('key',$request->input('key'))
            ->when($id, function($query, $id){
                return $query->where('id', '!=',$id);
            });
        if($SnmpRoleExists->exists())
        {
            return ["status" => "fail", "des" => '角色标识不能重复', 'res'=>[]];
        }
        $SnmpRoleObj->name = $request->input('name');
        $SnmpRoleObj->key = $request->input('key');
        $SnmpRoleObj->port = $request->input('port');
        $SnmpRoleObj->user = $request->input('user');
        $SnmpRoleObj->password = $request->input('password');
        if($SnmpRoleObj->save())
        {
            $resList = [
                'data' => [
                    'id' => $SnmpRoleObj->id
                ]
            ];
            return ["status" => "success", "des" => '保存成功', "res" => $resList];
        }
        return ["status" => "fail", "des" => '保存失败，存在相同数据', 'res'=>[]];
    }
    public function info(Request $request)
    {
        Log::info("服务器角色信息详情获取", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $ContactObj = SnmpRole::find($request->input('id'));
        if($ContactObj){
            $ContactObj = $ContactObj->toArray();
            return ["status" => "success", "des" => '获取成功', 'res'=>["data"=>$ContactObj]];
        }
        return ["status" => "fail", "des" => '获取失败', 'res'=>[]];
    }

    public function delete(Request $request)
    {

        Log::info("服务器角色信息删除", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $SnmpHostObj = SnmpRole::find($request->input('id'));
        if($SnmpHostObj->delete()){
            return ["status" => "success", "des" => '删除成功', 'res'=>[]];
        }
        return ["status" => "fail", "des" => '删除失败', 'res'=>[]];
    }
}
