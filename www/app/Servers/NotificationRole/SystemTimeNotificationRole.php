<?php


namespace App\Servers\NotificationRole;

use App\Servers\snmp;
use App\Models\ServerInfo;
use App\Servers\NotifiCation;
use Illuminate\Support\Facades\Log;
use Nette\Schema\Expect;
use Exception;

class SystemTimeNotificationRole
{

    public static function check($serverInfo, $notificationInfo)
    {
        Log::debug("检查主机系统时间告警", ["hostId" => $serverInfo["hostId"], "result" => ""]);
        $title = $notificationInfo['title'];
        $type = $notificationInfo['type'];
        $time1 = $notificationInfo['time1']??'';
        $time2 = $notificationInfo['time2']??'';
        $countType = $notificationInfo['countType'] ?? 1;
        $operator = $notificationInfo['operator'] ?? 1;
        $value = $notificationInfo['value'];
        $sendType = $notificationInfo['sendType'];
        $content = $notificationInfo['content'];
        $ContactId = $notificationInfo['ContactId'];
        $continueCycle  = $notificationInfo['continueCycle'];
        $silenceCycle = $notificationInfo['silenceCycle'];
        $noticeSettingId = $notificationInfo['noticeSettingId'];
        $sound_index = $notificationInfo['sound_index'];
        $HostIfo = Snmp::getHostInfo($serverInfo["hostId"]);
        $server_id = $HostIfo['id'];
        $device_ip = $HostIfo['host'];
        $hostName = $HostIfo['name'];
        
<<<<<<< HEAD
        $sinfo = ServerInfo::where('created_at', '>=', $time1)->where('created_at', '<=', $time2)->where('hostId', '=', $server_id)->limit(1)->orderby('id','desc')->get();
=======
        $sinfo = ServerInfo::where('created_at', '>=', $time1)->where('created_at', '<=', $time2)->where('hostId', '=', $server_id)->limit(1)->order('id desc')->get();
>>>>>>> 0c6cc07d12d6cdd4274286b424b18a5211541375
        if(!empty($sinfo)){
            $sinfo = $sinfo->toArray();
        }
        $count = count($sinfo??[]);
        $systemTime = '';
        $now_value = 0;
        foreach ($sinfo as  $kk => $vv) {
            //var_dump($vv['cpu_use']);
            $systemTime = $vv['system_time'];
        }
        if ($count > 0) {
            if(!empty($systemTime)){
                $now_value = abs(time()-strtotime($systemTime));
            }
            $params = [
                'type' => $type,
                'operator' => $operator,
                'value' => $value,
                'now_value' => $now_value,
                'sendType' => $sendType,
                'content' => $hostName . ','.$title . ',当前系统时间为' . $systemTime.','. $content,
                'hostId' => $server_id,
                'host' => $device_ip,
                'ContactId' => $ContactId,
                'continueCycle' => $continueCycle,
                'silenceCycle' => $silenceCycle,
                'noticeSettingId' => $noticeSettingId,
                'sound_index' => $sound_index,
            ];
            NotifiCation::warningInfo($params);
        }
    }
}
