<?php


namespace App\Servers\NotificationRole;

use App\Servers\snmp;
use App\Models\ServerInfo;
use App\Models\NotificationInfo;
use App\Servers\NotifiCation;
use Illuminate\Support\Facades\Log;

class DiskIoUseNotificationRole
{

    public static function check($serverInfo, $notificationInfo)
    {
        Log::debug("检查主机硬盘IO告警", ["hostId" => $serverInfo["hostId"], "result" => ""]);
        $title = $notificationInfo['title'];
        $type = $notificationInfo['type'];
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
                $diskinfo = json_decode($vv['disk_io'], true);
                $m=0;
                foreach ($diskinfo as $kkk=>$vvv) {
                    $diskinfo_[$m]['disk_name']=$vvv['disk_name'];
                    if($type==7){
                        $diskinfo_[$m]['used_percent'][] = $vvv['kB_read_avg'];
                    }
                    if($type==8){
                        $diskinfo_[$m]['used_percent'][] = $vvv['kB_wrtn_avg'];
                    }
                    if($type==9){
                        $diskinfo_[$m]['used_percent'][] = $vvv['util'];
                    }
                    $m++;
                }
            }
            $params_['n'] = $n ?? 0;
            $params_['count'] = $count ?? 0;
            $params_['diskinfo_'] = $diskinfo_;
            Log::debug("INFO", ["hostId" => $server_id, "result" => $params_]);
            $mm=0;
            foreach ($diskinfo_ as $v) {
                $use_total = 0;
                foreach($v['used_percent'] as $vv){
                    $use_total+=$vv;
                }
                $diskinfo__[$mm]['use_total']=$use_total;
                $mm++;
            }
            foreach ($diskinfo_ as $k => $v) {
                if ($countType == 1) {
                    $avg_use =  max($diskinfo_[$k]['used_percent']);
                }
                if ($countType == 2) {
                    $avg_use =  min($diskinfo_[$k]['used_percent']);
                }
                if ($countType == 3) {
                    $avg_use =  sprintf('%.2f', $diskinfo__[$k]['use_total'] / count($diskinfo_[$k]['used_percent']));
                }
                $avg_use_ = $avg_use;
                if (!in_array($type, [3, 4, 5])) {
                    $avg_use_ = $avg_use . '%';
                }
                $content1 = $hostName . ','.$title . ',当前值为' . $avg_use . ','.$content;
                $content2 = $hostName . ','.$title . ',当前值为' . $avg_use . ',恢复正常';
                if($type==7||$type==8){
                    $content1 = $hostName . ','.$title . ',当前值为' . $avg_use .  '(KB/s),'.$content;
                    $content2 = $hostName . ','.$title . ',当前值为' . $avg_use .  '(KB/s),恢复正常';
                }
                if ($avg_use >= $value) {
                    $params = [
                        'type' => $type,
                        'operator' => $operator,
                        'value' => $value,
                        'now_value' => $avg_use,
                        'sendType' => $sendType,
                        'content' => $content1,
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
                    $NotificationInfoStatus = NotificationInfo::where('hostId', $server_id)->where('notificationType', $type)->value('status') ?? -1;
                    if ($NotificationInfoStatus == 0) {
                        $params = [
                            'type' => $type,
                            'operator' => $operator,
                            'value' => $value,
                            'now_value' => $avg_use,
                            'sendType' => $sendType,
                            'content' => $content2,
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
                }
            }
        } else {
            Log::debug("检查主机硬盘IO告警记录为0", ["hostId" => $serverInfo["hostId"], "result" => ""]);
        }
    }
}
