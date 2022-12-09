<?php

namespace App\Observers;

use App\Models\SnmpHostRole;
use App\Models\SnmpHostInfo;
use App\Models\NotificationSetting;
use App\Servers\NotificationRole\ServerStatusNotificationRole;
use Illuminate\Support\Facades\Log;

class SnmpHostInfoObserver
{
    /**
     * Handle the SnmpHostInfo "created" event.
     *
     * @param  \App\Models\SnmpHostInfo  $SnmpHostInfo
     * @return void
     */
    public function created(SnmpHostInfo $SnmpHostInfo)
    {
        Log::debug("获取服务状态告警",["hostId"=>$SnmpHostInfo["hostId"],"roleId"=>$SnmpHostInfo["roleId"]]);
        $serviceName = '';
        $query = SnmpHostRole::query();
        $SnmpHostRoleResult = $query->where("id", $SnmpHostInfo["roleId"])->get();
        if(!empty($SnmpHostRoleResult)){
            $SnmpHostRoleResult = $SnmpHostRoleResult->toArray();
            if(!empty($SnmpHostRoleResult)){
                $serviceName = $SnmpHostRoleResult[0]['type'];
            }

        }
        $notificationSettingId = 0;
        $NotificationSettingResult=NotificationSetting::where("type",10)->where("status",1)->get();
        if(!empty($NotificationSettingResult)){
            $NotificationSettingResult = $NotificationSettingResult->toArray();
            if(!empty($NotificationSettingResult)){
                $NotificationSettingInfo = $NotificationSettingResult[0];
                $notificationSettingId = $NotificationSettingInfo['id'];
                $nstatus = $NotificationSettingInfo['status'];
            }
        }
        if($notificationSettingId>0){
                Log::debug("获取服务状态告警",["hostId"=>$SnmpHostInfo["hostId"],"serviceName"=>$serviceName]);
                $type = $NotificationSettingInfo["type"];
                $title = $NotificationSettingInfo['title'];
                $content = $NotificationSettingInfo['content'];
                $countType = $NotificationSettingInfo['countType'];
                $operator = $NotificationSettingInfo['operator'];
                $value = $NotificationSettingInfo['value'];
                $countType_array = ['1' => '最大值', '2' => '最小值', '3' => '平均值'];
                $operator_array = ['1' => '>', '2' => '>=', '3' => '=', '4' => '<', '5' => '<='];
                //$content = '{告警策略}{' . $content .'}'.$title. $countType_array[$countType] . $operator_array[$operator] . $value;
                $content = '{'.$title.'告警策略}{' . $content .'}';
                $NotificationSettingInfo['content'] = $content;
                $time2 = date('Y-m-d H:i:s',time());
                $time1 = date('Y-m-d H:i:s',strtotime($time2)-($NotificationSettingInfo['cycle']*60));
                $NotificationSettingInfo['time1'] = $time1;
                $NotificationSettingInfo['time2'] = $time2;
                $NotificationSettingInfo['noticeSettingId'] = $NotificationSettingInfo['id'];
                $SnmpHostInfo["serviceName"] = $serviceName;
                ServerStatusNotificationRole::check($SnmpHostInfo, $NotificationSettingInfo);
        }else{
            Log::debug("获取服务状态告警ERROR",["hostId"=>$SnmpHostInfo["hostId"],"roleId"=>$SnmpHostInfo["roleId"]]);
        }
    }

    /**
     * Handle the SnmpHostInfo "updated" event.
     *
     * @param  \App\Models\SnmpHostInfo  $SnmpHostInfo
     * @return void
     */
    public function updated(SnmpHostInfo $SnmpHostInfo)
    {
        //
    }

    /**
     * Handle the SnmpHostInfo "deleted" event.
     *
     * @param  \App\Models\SnmpHostInfo  $SnmpHostInfo
     * @return void
     */
    public function deleted(SnmpHostInfo $SnmpHostInfo)
    {
        //
    }

    /**
     * Handle the SnmpHostInfo "restored" event.
     *
     * @param  \App\Models\SnmpHostInfo  $SnmpHostInfo
     * @return void
     */
    public function restored(SnmpHostInfo $SnmpHostInfo)
    {
        //
    }

    /**
     * Handle the SnmpHostInfo "force deleted" event.
     *
     * @param  \App\Models\SnmpHostInfo  $SnmpHostInfo
     * @return void
     */
    public function forceDeleted(SnmpHostInfo $SnmpHostInfo)
    {
        //
    }
}
