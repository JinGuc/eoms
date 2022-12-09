<?php

namespace App\Http\Controllers\Api\v1\Notification;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\NotificationInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationInfoController extends Controller
{
    /**
     * 告警通知发送记录列表
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        Log::info("获取告警通知发送记录列表信息", ["request" => $request]);
        $page = $request->input('page', 1);
        $limit = $request->input('pagesize', 20);
        $ContactId = $request->input('ContactId', '');
        $status = $request->input('status', '');
        $notificationLogId = $request->input('notificationLogId', '');
        $startTime = $request->input('startTime') ?? date('Y-m-d 00:00:00');
        $endTime = $request->input('endTime') ?? date('Y-m-d 23:59:59');
        $query = NotificationInfo::query();

        if($notificationLogId)
        {
            $query->where("notificationLogId", $notificationLogId);
        }
        if($ContactId)
        {
            $query->where("ContactId", $ContactId);
        }
        if($status)
        {
            $query->where("status", $status);
        }
        if($startTime)
        {
            $query->where("created_at",'>=', $startTime);
        }
        if($endTime)
        {
            $query->where("created_at",'<=', $endTime);
        }

        $query->orderBy("updated_at", 'DESC');

        $total = $query->count();


        $NotificationLogList = $query->offset(($page - 1) * $limit)
            ->limit($limit);


        if($NotificationLogList->exists())
        {
            $NotificationLogList = $NotificationLogList->get(["id", "sendType", "ContactId as Contact", "data", "status" ,"created_at", "updated_at"])
            ->toArray();
            foreach($NotificationLogList as $k=>$v)
            {
                if(mb_strlen($v["Contact"])>0)
                {
                    $ContactInfo = Contact::find($v["Contact"]);
                    if($ContactInfo) {
                        $ContactInfo = $ContactInfo->toArray();
                        $NotificationLogList[$k]["Contact"] = array_key_exists("name", $ContactInfo)?$ContactInfo["name"]:"";
                    }
                    else {
                        $NotificationLogList[$k]["Contact"] = "";
                    }
                }
            }
            $resList = array(
                'data' => $NotificationLogList,
                'total' => $total

            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }

    /**
     * 根据告警日志id获取告警发送记录列表信息
     * @param Request $request
     * @return array
     */
    public function listByLogId(Request $request)
    {
        Log::info("根据告警日志id获取告警发送记录列表信息", ["request" => $request]);
        $notificationLogId = $request->input('notificationLogId', '');
        if($notificationLogId) {
            $query = NotificationInfo::query();
            $query->where("notificationLogId", $notificationLogId);
            if($query->exists())
            {
                $NotificationLogList = $query->get(["id", "sendType", "ContactId as Contact", "data", "status" ,"created_at", "updated_at"])
                    ->toArray();
                foreach($NotificationLogList as $k=>$v)
                {
                    if(mb_strlen($v["Contact"])>0)
                    {
                        $ContactInfo = Contact::find($v["Contact"]);
                        if($ContactInfo) {
                            $ContactInfo = $ContactInfo->toArray();
                            $NotificationLogList[$k]["Contact"] = array_key_exists("name", $ContactInfo)?$ContactInfo["name"]:"";
                        }
                        else {
                            $NotificationLogList[$k]["Contact"] = "";
                        }
                    }
                }
                $resList = array(
                    'data' => $NotificationLogList,
                );
                return ["status" => "success", "des" => "获取成功", "res" => $resList];
            }
            return ["status" => "success", "des" => "无数据", "res" => []];
        }
        return ["status" => "fail", "des" => "参数错误", "res" => []];
    }
}
