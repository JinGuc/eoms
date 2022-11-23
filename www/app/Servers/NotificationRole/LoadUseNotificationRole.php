<?php


namespace App\Servers\NotificationRole;

use App\Servers\snmp;
use App\Models\ServerInfo;
use App\Servers\NotifiCation;
use Illuminate\Support\Facades\Log;

class LoadUseNotificationRole
{

    public static function check($serverInfo, $notificationInfo)
    {
        Log::debug("检查主机负载告警",["hostId"=>$serverInfo["hostId"],"result"=>""]);
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
        $used = [];
        $use_total = 0;
        $avg_use = 0;
        if ($type == 1) {
            $tmp_name = 'cpu_use';
        }
        if ($type == 2) {
            $tmp_name = 'mem_used_percent';
        }
        if ($type == 3) {
            $tmp_name = 'cpu_load1';
        }
        if ($type == 4) {
            $tmp_name = 'cpu_load5';
        }
        if ($type == 5) {
            $tmp_name = 'cpu_load15';
        }
        //$countType_array = ['1' => '最大值', '2' => '最小值', '3' => '平均值'];
        //$operator_array = ['1' => '>', '2' => '>=', '3' => '=', '4' => '<', '5' => '<='];
        //$content = '{告警策略}' . $title . $countType_array[$countType] . $operator_array[$operator] . $value;
        $sinfo = ServerInfo::where('created_at', '>=', $time1)->where('created_at', '<=', $time2)->where('hostId', '=', $server_id)->get();
        if(!empty($sinfo)){
            $sinfo = $sinfo->toArray();
        }
        $count = count($sinfo??[]);
        $use_total = 0;
        $used = [];
        foreach ($sinfo as  $kk => $vv) {
            //var_dump($vv['cpu_use']);
            $use_total += $vv[$tmp_name];
            $used[] = $vv[$tmp_name];
        }
        if ($count > 0) {
            if ($countType == 1) {
                $avg_use =  max($used);
            }
            if ($countType == 2) {
                $avg_use =  min($used);
            }
            if ($countType == 3) {
                $avg_use =  sprintf('%.2f', $use_total / $count);
            }
            $avg_use_ = $avg_use;
            if(!in_array($type,[3,4,5])){
                $avg_use_ = $avg_use.'%';
            }
            $params = [
                'type' => $type,
                'operator' => $operator,
                'value' => $value,
                'now_value' => $avg_use,
                'sendType' => $sendType,
                'content' => $hostName . '(' . $device_ip . ')' . $content . ',当前值为:' . $avg_use_ . ',达到告警值。',
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