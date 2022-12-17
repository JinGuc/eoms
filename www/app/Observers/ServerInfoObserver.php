<?php

namespace App\Observers;

use App\Models\HostNotificationSetting;
use App\Models\NotificationSetting;
use App\Models\ServerInfo;
use App\Servers\NotificationRole\CpuUseNotificationRole;
use App\Servers\NotificationRole\MemoryUseNotificationRole;
use App\Servers\NotificationRole\DiskUseNotificationRole;
use App\Servers\NotificationRole\DiskIoUseNotificationRole;
use App\Servers\NotificationRole\LoadUseNotificationRole;
use App\Servers\NotificationRole\MysqlUseNotificationRole;
use Illuminate\Support\Facades\Log;

class ServerInfoObserver
{
    /**
     * Handle the ServerInfo "created" event.
     *
     * @param  \App\Models\ServerInfo  $serverInfo
     * @return void
     */
    public function created(ServerInfo $serverInfo)
    {
        $notificationIds = [];
        $query = HostNotificationSetting::query();
        $HostNotificationSettingResult = $query->where("hostId", $serverInfo["hostId"])->get()->toArray();
        foreach($HostNotificationSettingResult  as $v){
            $notificationIds[]=$v['notificationId'];
        }
        $NotificationSettingResult=NotificationSetting::whereIn("id",$notificationIds)->where("status",1)->get()->toArray();
        foreach($NotificationSettingResult as $v){
                Log::debug("获取告警规则列表",["hostId"=>$serverInfo["hostId"],"notificationId"=>$v["id"],"result"=>$v]);
                $type = $v["type"];
                $title = $v['title'];
                $content = $v['content'];
                $countType = $v['countType'];
                $operator = $v['operator'];
                $value = $v['value'];
                $countType_array = ['1' => '最大值', '2' => '最小值', '3' => '平均值'];
                $operator_array = ['1' => '>', '2' => '>=', '3' => '=', '4' => '<', '5' => '<='];
                //$content = '{告警策略}{' . $content .'}'.$title. $countType_array[$countType] . $operator_array[$operator] . $value;
                //$content = '{'.$title.'告警策略}{' . $content .'}';
                $v['content'] = $content;
                $time2 = date('Y-m-d H:i:s',time());
                $time1 = date('Y-m-d H:i:s',strtotime($time2)-($v['cycle']*60));
                $v['time1'] = $time1;
                $v['time2'] = $time2;
                $v['noticeSettingId'] = $v['id'];
            switch ($type) {
                case 1:
                    CpuUseNotificationRole::check($serverInfo, $v);
                    break;
                case 2:
                    MemoryUseNotificationRole::check($serverInfo, $v);
                    break;
                case 3:
                    LoadUseNotificationRole::check($serverInfo, $v);
                    break;
                case 4:
                    LoadUseNotificationRole::check($serverInfo, $v);
                    break;
                case 5:
                    LoadUseNotificationRole::check($serverInfo, $v);
                    break;
                case 6:
                    DiskUseNotificationRole::check($serverInfo, $v);
                    break;
                case 7:
                    DiskIoUseNotificationRole::check($serverInfo, $v);
                    break;
                case 8:
                    DiskIoUseNotificationRole::check($serverInfo, $v);
                    break;
                case 9:
                    DiskIoUseNotificationRole::check($serverInfo, $v);
                    break;
            }
        }
    }

    /**
     * Handle the ServerInfo "updated" event.
     *
     * @param  \App\Models\ServerInfo  $serverInfo
     * @return void
     */
    public function updated(ServerInfo $serverInfo)
    {
        //
    }

    /**
     * Handle the ServerInfo "deleted" event.
     *
     * @param  \App\Models\ServerInfo  $serverInfo
     * @return void
     */
    public function deleted(ServerInfo $serverInfo)
    {
        //
    }

    /**
     * Handle the ServerInfo "restored" event.
     *
     * @param  \App\Models\ServerInfo  $serverInfo
     * @return void
     */
    public function restored(ServerInfo $serverInfo)
    {
        //
    }

    /**
     * Handle the ServerInfo "force deleted" event.
     *
     * @param  \App\Models\ServerInfo  $serverInfo
     * @return void
     */
    public function forceDeleted(ServerInfo $serverInfo)
    {
        //
    }
}
