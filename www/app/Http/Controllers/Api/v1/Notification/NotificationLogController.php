<?php

namespace App\Http\Controllers\Api\v1\Notification;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\NotificationLog;
use App\Models\NotificationSetting;
use App\Models\SnmpHost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationLogController extends Controller
{
    /**
     * 告警记录列表
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        Log::info("获取告警记录列表信息", ["request" => $request]);
        $page = $request->input('page') ?? 1;
        $limit = $request->input('pagesize') ?? 20;
        $keyword = $request->input('keyword', '');
        $hostId = $request->input('hostId', '');
        $status = $request->input('status', '');
        $startTime = $request->input('startTime') ?? date('Y-m-d 00:00:00');
        $endTime = $request->input('endTime') ?? date('Y-m-d 23:59:59');
        $searchType = $request->input('searchType') ?? 2;

        $query = NotificationLog::query();

        if($keyword)
        {
            $query->where("info", 'like', "%$keyword%");
        }
        if($hostId)
        {
            $query->where("hostId", $hostId);
        }
        if($status)
        {
            $query->where("status", $status);
        }
        if($startTime)
        {
            if($searchType != 1) {
                $query->where("created_at",'>=', $startTime);
            }
        }
        if($endTime)
        {
            if($searchType != 1) {
                $query->where("created_at",'<=', $endTime);
            }
        }

        $query->orderBy("updated_at", 'DESC');

        $total = $query->count();


        $NotificationLogList = $query->offset(($page - 1) * $limit)
            ->limit($limit);


        if($NotificationLogList->exists())
        {
            $NotificationLogList = $NotificationLogList->get(["id", "hostId as host", "notificationSettingId as notification", "ContactId as Contact", "notificationType", "info", "status" ,"created_at", "updated_at"])
                ->toArray();
            foreach($NotificationLogList as $k=>$v)
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
                    $NotificationLogList[$k]["Contact"] = implode(",",$ContactInfoTmp);
                    unset($ContactInfoTmp);
                }
                if(mb_strlen($v["host"])>0)
                {
                    $notificationInfo = SnmpHost::find($v["host"]);
                    if($notificationInfo) {
                        $notificationInfo = $notificationInfo->toArray();
                        $NotificationLogList[$k]["host"] = array_key_exists("name", $notificationInfo)?$notificationInfo["name"]:"";
                        $NotificationLogList[$k]["hostIp"] = array_key_exists("host", $notificationInfo)?$notificationInfo["host"]:"";
                    }
                    else {
                        $NotificationLogList[$k]["host"] = "";
                        $NotificationLogList[$k]["hostIp"] = "";
                    }
                }
                else
                {
                    $NotificationLogList[$k]["host"] = "";
                    $NotificationLogList[$k]["hostIp"] = "";
                }
                if(mb_strlen($v["notification"])>0)
                {
                    $notificationInfo = NotificationSetting::find($v["notification"]);
                    if($notificationInfo) {
                        $notificationInfo = $notificationInfo->toArray();
                        $NotificationLogList[$k]["notification"] = array_key_exists("title", $notificationInfo)?$notificationInfo["title"]:"";
                    }
                    else {
                        $NotificationLogList[$k]["notification"] = "";
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

    public function getTotal(Request $request)
    {
        $query = NotificationLog::query();
        $query->where("status", 1);
        $total = $query->count();
        return ["status" => "success", "des" => "获取成功", "res" => ["data"=>$total]];
    }
}
