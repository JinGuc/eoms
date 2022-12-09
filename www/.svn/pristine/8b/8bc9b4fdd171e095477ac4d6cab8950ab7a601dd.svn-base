<?php


namespace App\Servers\NotificationRole;

use App\Servers\snmp;
use App\Models\SnmpHostInfo;
use App\Servers\NotifiCation;
use Illuminate\Support\Facades\Log;
use Nette\Schema\Expect;
use Exception;

class ServerStatusNotificationRole
{

    public static function check($SnmpHostInfo, $notificationInfo)
    {
        Log::debug("检查服务状态告警", ["hostId" => $SnmpHostInfo["hostId"], "result" => ""]);
        $roleId = $SnmpHostInfo['roleId']??0;
        $serviceName = $SnmpHostInfo['serviceName']??'';
        $title = $notificationInfo['title']??'';
        $type = $notificationInfo['type']??'';
        $time1 = $notificationInfo['time1'] ?? '';
        $time2 = $notificationInfo['time2'] ?? '';
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
        $HostIfo = Snmp::getHostInfo($SnmpHostInfo["hostId"]);
        $server_id = $HostIfo['id'];
        $device_ip = $HostIfo['host'];
        $hostName = $HostIfo['name'];
        $used = [];
        $use_total = 0;
        $avg_use = 0;
        //$countType_array = ['1' => '最大值', '2' => '最小值', '3' => '平均值'];
        //$operator_array = ['1' => '>', '2' => '>=', '3' => '=', '4' => '<', '5' => '<='];
        //$content = '{告警策略}' . $title . $countType_array[$countType] . $operator_array[$operator] . $value;
        $sinfo = SnmpHostInfo::where('created_at', '>=', $time1)->where('created_at', '<=', $time2)->where('hostId', '=', $server_id)->where('roleId', '=', $roleId)->get();
        if(!empty($sinfo)){
            $sinfo = $sinfo->toArray();
        }
        $count = count($sinfo??[]);
        $m = 0;
        foreach ($sinfo as  $kk => $vv) {
            //var_dump($vv['cpu_use']);
           if($vv['status']!='running'){
                $m++;
           }
        }
        if ($count == $m || $SnmpHostInfo['status']!='running') {
            $now_value = 0;
            try {
                Log::debug("检查主机服务状态STOP告警", ["hostId" => $server_id, "serviceName" => $serviceName, "content" => $content]);
                $params = [
                    'type' => $type,
                    'operator' => $operator,
                    'value' => $value,
                    'now_value' => $now_value,
                    'sendType' => $sendType,
                    'content' => $hostName . '(' . $device_ip . ')' . $content . '(服务名称:'.$serviceName.'),状态:[停止服务]。',
                    'hostId' => $server_id,
                    'host' => $device_ip,
                    'relate_table' => 'snmp_host_role',
                    'relate_id' => $roleId??0,
                    'ContactId' => $ContactId,
                    'continueCycle' => $continueCycle,
                    'silenceCycle' => $silenceCycle,
                    'noticeSettingId' => $noticeSettingId,
                    'sound_index' => $sound_index,
                ];
                //Log::debug("检查主机内存告警1", ["hostId" => $server_id, "data" => $params]);
                $server = new NotifiCation();
                $res = $server->warningInfo($params);
                //Log::debug("检查主机内存告警2", ["hostId" => $server_id, "data" => $res]);
            } catch (Exception $e) {
                Log::debug("检查主机服务状态告警ERROR", ["hostId" =>$server_id, "serviceName" => $serviceName,"msg" => $e->getMessage().' at Line '.$e->getLine().' in File '.$e->getFile()]);
            }
        }
    }
}
