<?php


namespace App\Servers\NotificationRole;

use App\Servers\snmp;
use App\Models\ServerInfo;
use App\Servers\NotifiCation;
use Illuminate\Support\Facades\Log;

class DiskUseNotificationRole
{

    public static function check($serverInfo, $notificationInfo)
    {
        Log::debug("检查主机硬盘告警",["hostId"=>$serverInfo["hostId"],"result"=>""]);
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
        $sinfo = ServerInfo::where('created_at', '>=', $time1)->where('created_at', '<=', $time2)->where('hostId', '=', $server_id)->orderBy('id', 'desc')->get();
        if(!empty($sinfo)){
            $sinfo = $sinfo->toArray();
        }
        $count = count($sinfo??[]);
        if ($count > 0) {
            foreach ($sinfo as $kk => $vv) {
                //var_dump($vv['cpu_use']);
                $diskinfo = json_decode($vv['disk_info'], true);
                $m=0;
                $diskinfo_ = [];
                foreach ($diskinfo as $kkk=>$vvv) {
                    $diskinfo_[$m]['disk_name']=$vvv['disk_name'];
                    $diskinfo_[$m]['disk_used_percent'][] = $vvv['disk_used_percent'];
                    $m++;
                }
                $storage_info = json_decode($vv['storage_info'], true);
                $n=0;
                $storageinfo_ = [];
                foreach ($storage_info as $kkk=>$vvv) {
                    if($vvv['mounted']!='/mnt/cdrom'&&strpos($vvv['mounted'],'/cdrom')===false&&$vvv['used_percent']>=$value){
                        $storageinfo_[$n]['partition']=$vvv['partition'];
                        $storageinfo_[$n]['mounted']=$vvv['mounted'];
                        $storageinfo_[$n]['partition_used_percent'] = $vvv['used_percent'];
                        $n++;
                    }
                }
            }
            $params_['n'] = $n??0;
            $params_['count'] = $count??0;
            $params_['diskinfo_'] = $diskinfo_;
            Log::debug("INFO",["hostId"=>$server_id,"result"=>$params_]);
            $mm=0;
            foreach ($diskinfo_ as $v) {
                $use_total = 0;
                foreach($v['disk_used_percent'] as $vv){
                    $use_total+=$vv;
                }
                $diskinfo__[$mm]['use_total']=$use_total;
                $mm++;
            }
            //磁盘使用率
            $ii = 0;
            foreach ($diskinfo_ as $k => $v) {
                if ($countType == 1) {
                    $avg_use =  max($diskinfo_[$k]['disk_used_percent']);
                }
                if ($countType == 2) {
                    $avg_use =  min($diskinfo_[$k]['disk_used_percent']);
                }
                if ($countType == 3) {
                    $avg_use =  sprintf('%.2f', $diskinfo__[$k]['use_total'] / count($diskinfo_[$k]['disk_used_percent']));
                }
                $avg_use_ = $avg_use;
                if (!in_array($type, [3, 4, 5])) {
                    $avg_use_ = $avg_use . '%';
                }
                if ($avg_use >= $value) {
                    $params = [
                        'type' => $type,
                        'operator' => $operator,
                        'value' => $value,
                        'now_value' => $avg_use,
                        'sendType' => $sendType,
                        'content' => $hostName . '(' . $device_ip . ')' . $content . ',当前磁盘使用率为:' . $avg_use . '%,达到告警值。',
                        'hostId' => $server_id,
                        'host' => $device_ip,
                        'ContactId' => $ContactId,
                        'continueCycle' => $continueCycle,
                        'silenceCycle' => $silenceCycle,
                        'noticeSettingId' => $noticeSettingId,
                        'sound_index' => $sound_index,
                    ];
                    Log::debug("告警", ["hostId" => $server_id, "result" => $params]);
                    NotifiCation::warningInfo($params);
                } else {
                    $ii++;
                }
            }
            if (count($storageinfo_) == 0 && $ii == count($diskinfo_)) {
                $params = [
                    'type' => $type,
                    'operator' => $operator,
                    'value' => $value,
                    'now_value' => $avg_use,
                    'sendType' => $sendType,
                    'content' => $hostName . '(' . $device_ip . ')' . $content . ',当前磁盘使用率恢复正常,',
                    'hostId' => $server_id,
                    'host' => $device_ip,
                    'ContactId' => $ContactId,
                    'continueCycle' => $continueCycle,
                    'silenceCycle' => $silenceCycle,
                    'noticeSettingId' => $noticeSettingId,
                    'status' => 1,
                ];
                Log::debug("恢复", ["hostId" => $server_id, "result" => $params]);
                NotifiCation::updateNoticeInfo($params);
            }
            //分区使用率
            foreach ($storageinfo_ as $k => $v) {
                $partition = $storageinfo_[$k]['partition'];
                $mounted = $storageinfo_[$k]['mounted'];
                $avg_use = $storageinfo_[$k]['partition_used_percent'];
                if (!in_array($type, [3, 4, 5])) {
                    $avg_use_ = $avg_use . '%';
                }
                if ($avg_use >= $value) {
                    $params = [
                        'type' => $type,
                        'operator' => $operator,
                        'value' => $value,
                        'now_value' => $avg_use,
                        'sendType' => $sendType,
                        'content' => $hostName . '(' . $device_ip . ')' . $content . ',当前磁盘分区目录['.$mounted.']使用率为:' . $avg_use . '%,达到告警值。',
                        'hostId' => $server_id,
                        'host' => $device_ip,
                        'ContactId' => $ContactId,
                        'continueCycle' => $continueCycle,
                        'silenceCycle' => $silenceCycle,
                        'noticeSettingId' => $noticeSettingId,
                        'sound_index' => $sound_index,
                    ];
                    Log::debug("告警", ["hostId" => $server_id, "result" => $params]);
                    NotifiCation::warningInfo($params);
                } else {
                    //
                }
            }
            if (empty($storageinfo_) && $ii == 0) {
                $params = [
                    'type' => $type,
                    'operator' => $operator,
                    'value' => $value,
                    'now_value' => $avg_use,
                    'sendType' => $sendType,
                    'content' => $hostName . '(' . $device_ip . ')' . $content . ',当前磁盘使用率恢复正常,',
                    'hostId' => $server_id,
                    'host' => $device_ip,
                    'ContactId' => $ContactId,
                    'continueCycle' => $continueCycle,
                    'silenceCycle' => $silenceCycle,
                    'noticeSettingId' => $noticeSettingId,
                    'status' => 1,
                ];
                Log::debug("恢复", ["hostId" => $server_id, "result" => $params]);
                NotifiCation::updateNoticeInfo($params);
            }
        }else{
            Log::debug("检查主机硬盘告警记录为0",["hostId"=>$serverInfo["hostId"],"result"=>""]);
        }
    }
}