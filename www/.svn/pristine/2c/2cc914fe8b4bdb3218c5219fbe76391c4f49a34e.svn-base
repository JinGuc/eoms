<?php

namespace App\Http\Controllers\Api\v1\Notification;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NotificationSettingController extends Controller
{
    /**
     * 告警规则列表
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        Log::info("获取告警规则列表信息", ["request" => $request]);
        $page = $request->input('page', 1);
        $limit = $request->input('pagesize', 20);
        $keyword = $request->input('keyword', '');

        $query = NotificationSetting::query();

        if($keyword)
        {
            $query->where("title", 'like', "%$keyword%");
        }

        $query->orderBy("id", 'ASC');

        $total = $query->count();


        $NotificationSettingList = $query->offset(($page - 1) * $limit)
            ->limit($limit);


        if($NotificationSettingList->exists())
        {
            $NotificationSettingList = $NotificationSettingList->get(["id", "status", "title", "type", "sendType", "ContactId as Contact", "content", "created_at", "updated_at"])
            ->toArray();
            foreach($NotificationSettingList as $k=>$v)
            {
                if(mb_strlen($v["Contact"])>0)
                {
                    $ContactIds = explode(",", $v["Contact"]);
                    $ContactInfo = Contact::whereIn("id",$ContactIds)->get(["name"])->toArray();
                    $ContactInfoTmp = [];
                    foreach ($ContactInfo as $value)
                    {
                        $ContactInfoTmp[] = "[".$value["name"]."]";
                    }
                    $NotificationSettingList[$k]["Contact"] = implode(",",$ContactInfoTmp);
                    unset($ContactInfoTmp);
                }
            }
            $resList = array(
                'data' => $NotificationSettingList,
                'total' => $total

            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }


    /**
     * 告警规则添加或修改
     * @param Request $request
     * @return array|string[]
     */
    public function addOrEdit(Request $request)
    {
        Log::info("添加或修改告警规则信息", ["request" => $request]);
        $act = $request->input('act','');//当前动作，表示想做另外的事
        $id = $request->input('id','');//传了为编辑，未传为新增
        $NotificationSettingObj = $id ? NotificationSetting::find($id) : new NotificationSetting;
        if($id && empty($NotificationSettingObj)){
            return ["status" => "fail", "des" => '未知的对象', "res"=>[]];
        }
        if($act == 'changeStatus'){//该动作表示只更新状态字段
            if(!$id){
                return ["status" => "fail", "des" => '无效的id'];
            }
            $NotificationSettingObj->status = $request->input('status',NotificationSetting::$_status['on']['code']);

        }else{
            // $validate = Validator::make($request->all(),[
            //     "title"=>"required",
            //     "sendType"=>"required",
            //     "type"=>"required",
            //     "cycle"=>"required",
            // ],[
            //     "title.required"=>"ip地址不能为空",
            //     "sendType.regex"=>"ip地址非法",
            //     "type.required"=>"主机名称不能为空",
            //     "type.required"=>"主机类型不能为空",
            // ]);
            // if($validate->fails()){
            //     return ["status" => "fail", "des" => $validate->errors()->first(), 'res'=>[]];
            // }
            $NotificationSettingObj->title = $request->input('title');
            $NotificationSettingObj->type = $request->input('type');
            $NotificationSettingObj->sendType = $request->input('sendType');
            $NotificationSettingObj->cycle = $request->input('cycle');
            $NotificationSettingObj->continueCycle = $request->input('continueCycle');
            $NotificationSettingObj->countType = $request->input('countType');
            $NotificationSettingObj->operator = $request->input('operator');
            $NotificationSettingObj->silenceCycle = $request->input('silenceCycle');
            $NotificationSettingObj->value = $request->input('value');
            $NotificationSettingObj->start = $request->input('start');
            $NotificationSettingObj->end = $request->input('end');
            $NotificationSettingObj->ContactId = $request->input('ContactId');
            $NotificationSettingObj->content = $request->input('content');
            $NotificationSettingObj->status = $request->input('status',NotificationSetting::$_status['on']['code']);
            $NotificationSettingObj->sound_index = $request->filled('sound_index')?$request->input('sound_index'):"";
        }
        if($NotificationSettingObj->save())
        {
            $resList = [
                'data' => [
                    'id' => $NotificationSettingObj->id
                ]
            ];
            return ["status" => "success", "des" => '保存成功', "res" => $resList];
        }
        return ["status" => "fail", "des" => '保存失败，存在相同数据', 'res'=>[]];
    }

    /**
     * 告警规则详情
     * @param Request $request
     * @return array
     */
    public function info(Request $request)
    {
        Log::info("告警规则信息详情获取", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $NotificationSettingObj = NotificationSetting::find($request->input('id'));
        if($NotificationSettingObj){
            $NotificationSettingObj = $NotificationSettingObj->toArray();
            if(mb_strlen($NotificationSettingObj["ContactId"])>0)
            {
                $ContactIds = explode(",", $NotificationSettingObj["ContactId"]);
                $ContactInfo = Contact::whereIn("id",$ContactIds)->get(["name"])->toArray();
                $ContactInfoTmp = [];
                foreach ($ContactInfo as $value)
                {
                    $ContactInfoTmp[] = "[".$value["name"]."]";
                }
                $NotificationSettingObj["Contact"] = implode(",",$ContactInfoTmp);
                unset($ContactInfoTmp);
            }
            return ["status" => "success", "des" => '获取成功', 'res'=>["data"=>$NotificationSettingObj]];
        }
        return ["status" => "fail", "des" => '获取失败', 'res'=>[]];
    }

    /**
     * 告警规则删除
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        Log::info("告警规则信息删除", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $NotificationSettingObj = NotificationSetting::find($request->input('id'));
        if($NotificationSettingObj->delete()){
            return ["status" => "success", "des" => '删除成功', 'res'=>[]];
        }
        return ["status" => "fail", "des" => '删除失败', 'res'=>[]];
    }



    /**
     * 启用的告警规则列表
     * @param Request $request
     * @return array
     */
    public function EnableList(Request $request)
    {
        Log::info("获取启用的告警规则列表信息", ["request" => $request]);

        $query = NotificationSetting::query();

        $query->orderBy("updated_at", 'DESC');
        $query->where('status',1);


        if($query->exists())
        {
            $HostList = $query->get(["id", "title", "sendType", "type", "created_at", "updated_at"])
                ->toArray();
            $resList = array(
                'data' => $HostList
            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }
}
