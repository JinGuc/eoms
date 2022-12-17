<?php


namespace App\Servers\NotificationRole;

use App\Servers\snmp;
use App\Models\UrlStatusInfo;
use App\Models\UrlInfo;
use App\Servers\NotifiCation;
use Illuminate\Support\Facades\Log;
use Nette\Schema\Expect;
use Exception;

class UrlStatusInfoNotificationRole
{

    public static function check($urlStatusInfo, $notificationInfo)
    {
        $url_id = $urlStatusInfo["url_id"]??0;
        $url = $urlStatusInfo["url"]??'';
        $url_title = $urlStatusInfo["url_title"]??'';
        $status_code = $urlStatusInfo['status_code']??0;
        Log::debug("获取URL接口状态告警3",["url_title"=>$urlStatusInfo["url_title"]]);
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
        $http_type = strtolower(UrlInfo::where('id',$url_id)->value('type')??'');
        //$countType_array = ['1' => '最大值', '2' => '最小值', '3' => '平均值'];
        //$operator_array = ['1' => '>', '2' => '>=', '3' => '=', '4' => '<', '5' => '<='];
        //$content = '{告警策略}' . $title . $countType_array[$countType] . $operator_array[$operator] . $value;
        $sinfo = UrlStatusInfo::where('created_at', '>=', $time1)->where('created_at', '<=', $time2)->where('url_id', '=', $url_id)->get();
        if(!empty($sinfo)){
            $sinfo = $sinfo->toArray();
        }
        $count = count($sinfo??[]);
        $m = 0;
        foreach ($sinfo as  $kk => $vv) {
            //var_dump($vv['cpu_use']);
           if(empty($vv['status_code'])||$vv['status_code']>=500){
                $m++;
           }
        }
        if ($count == $m || empty($status_code) || $status_code>=500) {
            $now_value = 0;
            try {
                Log::debug("获取URL接口状态告警4",["url_title"=>$urlStatusInfo["url_title"]]);
                $params = [
                    'type' => $type,
                    'operator' => $operator,
                    'value' => $value,
                    'now_value' => $now_value,
                    'sendType' => $sendType,
                    'content' => $url_title .$title .',状态码'.$status_code.','.$content,
                    'hostId' => 0,
                    'host' => '',
                    'relate_table' => 'url_info',
                    'relate_id' => $url_id??0,
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
                Log::debug("获取URL接口状态告警ERROR", ["url_id"=>$url_id,"msg" => $e->getMessage().' at Line '.$e->getLine().' in File '.$e->getFile()]);
            }
        }else{
            if(!empty($status_code)&&$status_code<500){
                $now_value = 1;
                $params = [
                    'type' => $type,
                    'operator' => $operator,
                    'value' => $value,
                    'now_value' => $now_value,
                    'sendType' => $sendType,
                    'content' => $url_title .$title .',恢复正常',
                    'hostId' => 0,
                    'host' => '',
                    'relate_table' => 'url_info',
                    'relate_id' => $url_id??0,
                    'ContactId' => $ContactId,
                    'continueCycle' => $continueCycle,
                    'silenceCycle' => $silenceCycle,
                    'noticeSettingId' => $noticeSettingId,
                    'sound_index' => $sound_index,
                    'status' => 1,
                ];
                //Log::debug("检查主机内存告警1", ["hostId" => $server_id, "data" => $params]);
                $server = new NotifiCation();
                $res = $server->warningInfo($params);                
            }
        }
    }
}
