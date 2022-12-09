<?php

namespace App\Http\Controllers\Api\v1\Host;

use App\Http\Controllers\Controller;
use App\Models\HostNotificationSetting;
use App\Models\NotificationSetting;
use App\Models\SnmpHost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HostNotificationSettingController extends Controller
{
    /**
     * 根据主机id或告警规则id获取已分配的告警规则
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $hostId = $request->input("hostId");
        $notificationId = $request->input("notificationId");
        if($hostId || $notificationId)
        {
            $query = HostNotificationSetting::query();
            if($hostId)
            {
                $query->where("hostId",$hostId);
            }
            if($notificationId)
            {
                $query->where("notificationId",$notificationId);
            }
            $HostNotificationSettingList = $query->with(["host","notification"]);
            if($HostNotificationSettingList->exists())
            {
                $HostNotificationSettingList = $HostNotificationSettingList->get()->toArray();
                foreach($HostNotificationSettingList as $k=>$v)
                {
                    $SnmpHostInfo = $v["host"];
                    if($SnmpHostInfo)
                    {
                        $HostNotificationSettingList[$k]["host"] = $SnmpHostInfo["name"];
                    }
                    else
                    {
                        $HostNotificationSettingList[$k]["host"] = "";
                    }
                    $NotificationSettingInfo = $v["notification"];
                    if($NotificationSettingInfo)
                    {
                        $HostNotificationSettingList[$k]["notification"] = $NotificationSettingInfo["title"];
                    }
                    else
                    {
                        $HostNotificationSettingList[$k]["notification"] = "";
                    }
                }
                $resList = array(
                    'data' => $HostNotificationSettingList,

                );
                return ["status" => "success", "des" => "获取成功", "res" => $resList];
            }
            return ["status" => "success", "des" => "无数据", "res" => []];
        }
        return ["status" => "fail", "des" => '缺少参数', 'res'=>[]];
    }
    /**
     * 添加服务器告警规则
     * @param Request $request
     * @return array
     */
    public function save(Request $request)
    {
        Log::info("添加服务器告警规则", ["request" => $request]);
        $hostId = $request->input("hostId");
        $notificationId = $request->input("notificationId");
        if($hostId && $notificationId)
        {
            $HostNotificationSettingObj = HostNotificationSetting::where("hostId", $hostId)->where("notificationId", $notificationId);
            if($HostNotificationSettingObj->doesntExist()) {
                $HostNotificationSettingObj = new HostNotificationSetting;
                $HostNotificationSettingObj->hostId = $hostId;
                $HostNotificationSettingObj->notificationId = $notificationId;
                if ($HostNotificationSettingObj->save()) {
                    return ["status" => "success", "des" => '分配成功', 'res' => []];
                }
                return ["status" => "fail", "des" => '分配失败', 'res'=>[]];
            }
            return ["status" => "success", "des" => '已分配,无需重复分配', 'res' => []];
        }
        return ["status" => "fail", "des" => '缺少参数', 'res'=>[]];
    }

    /**
     * 移除服务器告警规则
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        Log::info("移除服务器告警规则", ["request" => $request]);
        $hostId = $request->input("hostId");
        $notificationId = $request->input("notificationId");
        if($hostId && $notificationId)
        {
            $HostNotificationSettingObj = HostNotificationSetting::where("hostId", $hostId)->where("notificationId", $notificationId);
            if($HostNotificationSettingObj->exists())
            {
                if($HostNotificationSettingObj->delete())
                {
                    return ["status" => "success", "des" => '移除成功', 'res'=>[]];
                }
                return ["status" => "fail", "des" => '移除失败', 'res'=>[]];
            }
            return ["status" => "success", "des" => '移除成功', 'res'=>[]];
        }
        return ["status" => "fail", "des" => '缺少参数', 'res'=>[]];
    }
}
