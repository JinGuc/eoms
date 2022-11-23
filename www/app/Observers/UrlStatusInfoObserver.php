<?php

namespace App\Observers;

use App\Models\UrlStatusInfo;
use App\Models\NotificationSetting;
use App\Servers\NotificationRole\UrlStatusInfoNotificationRole;
use Illuminate\Support\Facades\Log;

class UrlStatusInfoObserver
{
    /**
     * Handle the UrlStatusInfo "created" event.
     *
     * @param  \App\Models\UrlStatusInfo  $urlStatusInfo
     * @return void
     */
    public function created(UrlStatusInfo $urlStatusInfo)
    {
        //
        Log::debug("获取URL接口状态告警",["url_title"=>$urlStatusInfo["url_title"]]);
        $serviceName = '';
        $notificationSettingId = 0;
        $NotificationSettingResult=NotificationSetting::where("type",11)->where("status",1)->get();
        if(!empty($NotificationSettingResult)){
            $NotificationSettingResult = $NotificationSettingResult->toArray();
            if(!empty($NotificationSettingResult)){
                $NotificationSettingInfo = $NotificationSettingResult[0];
                $notificationSettingId = $NotificationSettingInfo['id'];
                $nstatus = $NotificationSettingInfo['status'];
            }
        }
        if($notificationSettingId>0){
                Log::debug("获取URL接口状态告警",["url_title"=>$urlStatusInfo["url_title"]]);
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
                UrlStatusInfoNotificationRole::check($urlStatusInfo, $NotificationSettingInfo);
        }else{
            Log::debug("获取URL接口状态告警ERROR",["url_title"=>$urlStatusInfo["url_title"]]);
        }
    }

    /**
     * Handle the UrlStatusInfo "updated" event.
     *
     * @param  \App\Models\Models\UrlStatusInfo  $urlStatusInfo
     * @return void
     */
    public function updated(UrlStatusInfo $urlStatusInfo)
    {
        //
    }

    /**
     * Handle the UrlStatusInfo "deleted" event.
     *
     * @param  \App\Models\Models\UrlStatusInfo  $urlStatusInfo
     * @return void
     */
    public function deleted(UrlStatusInfo $urlStatusInfo)
    {
        //
    }

    /**
     * Handle the UrlStatusInfo "restored" event.
     *
     * @param  \App\Models\Models\UrlStatusInfo  $urlStatusInfo
     * @return void
     */
    public function restored(UrlStatusInfo $urlStatusInfo)
    {
        //
    }

    /**
     * Handle the UrlStatusInfo "force deleted" event.
     *
     * @param  \App\Models\Models\UrlStatusInfo  $urlStatusInfo
     * @return void
     */
    public function forceDeleted(UrlStatusInfo $urlStatusInfo)
    {
        //
    }
}
