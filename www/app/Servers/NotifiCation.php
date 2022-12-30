<?php
namespace App\Servers;

use App\Models\NotificationInfo;
use App\Models\NotificationLog;
use App\Models\NotificationSendinfo;
use App\Models\NotificationSetting;
use App\Models\SnmpHostRole;
use App\Models\Contact;
use App\Models\UrlInfo;
use App\Servers\NotificationRole\NotificationRole;
use Illuminate\Support\Facades\Log;
use Exception;

class NotifiCation{

    public static function warningInfo($params)
    {
        try {
            $operator = $params['operator'];
            $value = $params['value'];
            $now_value = $params['now_value'];
            $p_status = $params['status'] ?? 0;
            //file_put_contents(storage_path('logs/jingu-eoms-warning.log'),var_export($params,true)."\r\n",FILE_APPEND);
            //Log::debug("==检查主机告警信息==", ["hostId" => $params["hostId"], "result" => $params]);
            if ($p_status == 1) {
                self::updateNoticeInfo($params);
            } else {
                if ($params['type'] == 11) {
                    self::insertNoticeInfo($params);
                } else {
                    switch ($operator) {
                        case 1:
                            if ($now_value > $value) {
                                //告警
                                self::insertNoticeInfo($params);
                            } else {
                                $params['status'] = 1;
                                self::updateNoticeInfo($params);
                            }
                            break;
                        case 2:
                            if ($now_value >= $value) {
                                //告警
                                self::insertNoticeInfo($params);
                            } else {
                                $params['status'] = 1;
                                self::updateNoticeInfo($params);
                            }
                            break;
                        case 3:
                            if ($now_value = $value) {
                                //告警
                                self::insertNoticeInfo($params);
                            } else {
                                $params['status'] = 1;
                                self::updateNoticeInfo($params);
                            }
                            break;
                        case 4:
                            if ($now_value <= $value) {
                                //告警
                                self::insertNoticeInfo($params);
                            } else {
                                $params['status'] = 1;
                                self::updateNoticeInfo($params);
                            }
                            break;
                        case 5:
                            if ($now_value < $value) {
                                //告警
                                self::insertNoticeInfo($params);
                            } else {
                                $params['status'] = 1;
                                self::updateNoticeInfo($params);
                            }
                            break;
                    }
                }
            }
        } catch (Exception $e) {
            file_put_contents(storage_path('logs/jingu-eoms-warning-error.log'), $e->getMessage() . ' at line ' . $e->getLine() . ' in file ' . $e->getFile() . "\r\n", FILE_APPEND);
            //Log::debug('ERROR',['msg'=>$e->getMessage()]);
        }
        return true;
    }
    public static function insertNoticeInfo($params)
    {
        $NotificationSettingInfo = NotificationSetting::where('id',$params['noticeSettingId'])->get();
        if(!empty($NotificationSettingInfo)){
            $NotificationSettingInfo = $NotificationSettingInfo->toArray();
            if (!empty($NotificationSettingInfo)) {
                $nstatus = $NotificationSettingInfo[0]['status'];
                if ($nstatus != 1) {
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
        $stopNoticeTime = date('Y-m-d H:i:s', time() + ($params['continueCycle']) * 60);
        $time = date('Y-m-d H:i:s');
        if (!empty($params['relate_table'])){
            if ($params['relate_table'] == 'url_info') {
                $UrlInfo = UrlInfo::where('id', $params['relate_id'])->get();
                if (!empty($UrlInfo)) {
                    $UrlInfo = $UrlInfo->toArray();
                    if (!empty($UrlInfo)) {
                        $nstatus = $UrlInfo[0]['status'];
                        if ($nstatus != 1) {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }else{
                if ($params['relate_table'] == 'snmp_host_role') {
                    $SnmpHostRoleInfo = SnmpHostRole::where('id', $params['relate_id'])->get();
                    if (!empty($SnmpHostRoleInfo)) {
                        $SnmpHostRoleInfo = $SnmpHostRoleInfo->toArray();
                        if (empty($SnmpHostRoleInfo)) {
                            return false;
                        }
                    }
                }
            }
            $id = NotificationInfo::where('relate_id', '=', $params['relate_id'])->where('relate_table', '=', $params['relate_table'])->where('notificationType', '=', $params['type'])->where('status', '=', 0)->orderBy('id', 'desc')->limit(1)->value('id');
            $id = intval($id);
        }else{
            $id = NotificationInfo::where('hostId', '=', $params['hostId'])->where('notificationType', '=', $params['type'])->where('status', '=', 0)->orderBy('id', 'desc')->limit(1)->value('id');
            $id = intval($id);
        }
        if ($id == 0) {
            $data = [
                'hostId' => $params['hostId']??0,
                'notificationLogId'=>0,
                'relate_table'=>$params['relate_table'] ?? '',
                'relate_id'=>$params['relate_id'] ?? 0,
                'notificationSettingId' => $params['noticeSettingId'],
                'notificationType' => $params['type'],
                'sendType' => $params['sendType'],
                'ContactId' => $params['ContactId'],
                'stopNoticeTime' => $stopNoticeTime,
                'silenceCycle' => $params['silenceCycle'],
                'data' =>  $params['content'],
                'sound_index' => $params['sound_index']??'',
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $infoId = NotificationInfo::insertGetId($data);
        } else {
            //
            $NotificationInfo_created_at = NotificationInfo::where('id', $id)->value('created_at');
            $stopNoticeTime = date('Y-m-d H:i:s', strtotime($NotificationInfo_created_at) + ($params['continueCycle']) * 60);
            $data = [
                'notificationType' => $params['type'],
                'sendType' => $params['sendType'],
                'ContactId' => $params['ContactId'],
                'stopNoticeTime' => $stopNoticeTime,
                'silenceCycle' => $params['silenceCycle'],
                'data' =>  $params['content'],
                'sound_index' => $params['sound_index']??'',
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            NotificationInfo::where('id',$id)->update($data);
            NotificationLog::where('notificationSettingId',$params['noticeSettingId'])->where('status',0)->update(['info'=>$params['content']??'','ContactId'=>$params['ContactId']??'','sendType'=>$params['sendType']??1,'sound_index'=>$params['sound_index']??'']);
            NotificationSendinfo::where('notificationSettingId',$params['noticeSettingId'])->update(['content'=>$params['content']??'','sound_index'=>$params['sound_index']??'']);
        }
        self::insertNoticeLog($params);
        //self::SendWarningMsg($params);
    }
    public static function updateNoticeInfo($params)
    {
        if (!empty($params['relate_table'])) {
            $info = NotificationInfo::where('relate_id', '=', $params['relate_id'])->where('relate_table', '=', $params['relate_table'])->where('notificationType', '=', $params['type'])->where('status', '=', 0)->orderBy('id', 'desc')->limit(1)->get();
        } else {
            $info = NotificationInfo::where('hostId', '=', $params['hostId'])->where('notificationType', '=', $params['type'])->where('status', '=', 0)->orderBy('id', 'desc')->limit(1)->get();
        }
        if (!empty($info)) {
            $info = $info->toArray();
            if (!empty($info)) {
                $id = intval($info[0]['id']);
                $ContactId = $info[0]['ContactId'];
                $notificationSettingId = $info[0]['notificationSettingId'];
                $hostId = $info[0]['hostId'];
                $notificationSettingId_Content = NotificationSetting::where('id',$notificationSettingId)->value('content');
                //$stopNoticeTime = date('Y-m-d H:i:s', time() + ($params['continueCycle'] + 1) * 60);
                $NotificationInfo_created_at = NotificationInfo::where('id', $id)->value('created_at');
                $stopNoticeTime = date('Y-m-d H:i:s', strtotime($NotificationInfo_created_at) + ($params['continueCycle']) * 60);
                $data = [
                    'notificationType' => $params['type'],
                    'sendType' => $params['sendType'],
                    'ContactId' => $params['ContactId'],
                    'stopNoticeTime' => $stopNoticeTime,
                    'silenceCycle' => $params['silenceCycle'],
                    'data' =>  $params['content'],
                    'sound_index' => $params['sound_index']??'',
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                if ($params['status'] == 1) {
                    $data['status'] = 1;
                    //$data['data'] = $params['content'];
                    if (!empty($params['relate_table'])) {
                        $notificationList = NotificationInfo::where('relate_id', $params['relate_id'])->where('relate_table', $params['relate_table'])->where('status', 0)->get();
                        if(!empty($notificationList)){
                            $notificationList = $notificationList->toArray()??[];
                            if(!empty($notificationList)){
                                foreach($notificationList as $v){
                                    $notificationId = $v['id'];
                                    NotificationLog::where('notificationId', $notificationId)->where('status', 1)->update(['status' => '-1']);
                                    NotificationSendinfo::where('notificationId', $notificationId)->where('status', 0)->update(['status' => '-1']);
                                }
                            }else{
                                NotificationLog::where('notificationSettingId', $notificationSettingId)->where('status', 1)->update(['status' => '-1']);
                                NotificationSendinfo::where('notificationSettingId', $notificationSettingId)->where('status', 0)->update(['status' => '-1']);                                
                            }
                        } 
                    }
                    $msg = '';
                }else{
                    $msg = '';
                }
                NotificationInfo::where('id',$id)->update($data);
                if (!empty($params['relate_table'])) {
                    $notificationList = NotificationInfo::where('relate_id', $params['relate_id'])->where('relate_table', $params['relate_table'])->where('status', 0)->get();
                    if(!empty($notificationList)){
                        $notificationList = $notificationList->toArray();
                        if(!empty($notificationList)){
                            foreach($notificationList as $v){
                                $notificationId = $v['id'];
                                NotificationLog::where('notificationId', $notificationId)->where('status', 0)->update(['info' => $params['content'] ?? '', 'ContactId' => $params['ContactId'] ?? '', 'sendType' => $params['sendType'] ?? 1, 'sound_index' => $params['sound_index'] ?? '']);
                                NotificationSendinfo::where('notificationId', $notificationId)->update(['content' => $params['content'] ?? '', 'sound_index' => $params['sound_index'] ?? '']);
                            }
                        }
                    }
                    if($params['relate_table'] == 'url_info'){
                        $url = UrlInfo::where('id',$params['relate_id'])->value('url');
                        $content =  $params['content'].$msg;
                    }elseif($params['relate_table'] == 'snmp_host_role'){
                        $serviceName = SnmpHostRole::where('id',$params['relate_id'])->value('type');
                        $content =  $params['content'].$msg;
                    }
                } else {
                    NotificationLog::where('notificationSettingId', $notificationSettingId)->where('status', 0)->update(['info' => $params['content'] ?? '', 'ContactId' => $params['ContactId'] ?? '', 'sendType' => $params['sendType'] ?? 1, 'sound_index' => $params['sound_index'] ?? '']);
                    NotificationSendinfo::where('notificationSettingId', $notificationSettingId)->update(['content' => $params['content'] ?? '', 'sound_index' => $params['sound_index'] ?? '']);
                }
                $content = str_replace(['停止',$notificationSettingId_Content], ['正常','恢复正常'], $params['content']);
                if ($id > 0 &&  $params['status'] == 1) {
                    /*
                    $ContactIds = explode(',', $ContactId);
                    $toSend = [];
                    $person = Contact::whereIn('id', $ContactIds)->get();
                    if (!empty($person)) {
                        $person = $person->toArray();
                        $m = 0;
                        foreach ($person as $vv) {
                            $toSend[$m]['id'] = $vv['id'];
                            $toSend[$m]['email'] = $vv['email'];
                            $toSend[$m]['phone'] = $vv['phone'];
                            $params = [
                                'content' => $content . '恢复时间[' . date('Y-m-d H:i:s') . ']',
                                'mobile' => $vv['phone'],
                            ];
                            //$rs = NotificationRole::toSms($params);
                            
                            $m++;
                        }
                    }
                    */
                    $data = [
                        'hostId' => $hostId??0,
                        'notificationSettingId' => $notificationSettingId??0,
                        'notificationId' => $id??0,
                        'notificationType' => $params['type'],
                        'sendType' => $params['sendType'],
                        'ContactId' => $ContactId,
                        'info' => $content,
                        'sound_index' => $sound_index??'',
                        'created_at' => date('Y-m-d H:i:s'), 
                        'status' => 1                  
                    ];
                    $noticeLogId = NotificationLog::insertGetId($data);
                    /*
                    $pdata = [
                        'type' => $params['sendType'],
                        'ContactId' => $ContactId,
                        'notificationId' => $id??0,
                        'notificationSettingId' => $notificationSettingId??0,
                        'hostId' => $hostId??0,
                        'noticeLogId' => $noticeLogId ?? 0,
                        'status' => 0,
                        'info' => $content,
                        'sound_index' => $sound_index??'',
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    self::insertSendInfo($pdata);
                    */
                }
            }
        }
        return true;
    }
    public static function getNoticeSendInfoCount($params)
    {
        $count = NotificationSendinfo::where('notificationLogId', $params['notificationLogId'])->where('notificationId', $params['notificationId'])->where('type', $params['type'])->where('sendTo',$params['sendTo'])->where('status',$params['status'])->count();
        return $count;
    }
    public static function insertNoticeLog($params)
    {
        $time = date('Y-m-d H:i:s');
        $list = NotificationInfo::where('stopNoticeTime', '>=', $time)->where('status', '=', 0)->orderBy('id', 'asc')->get();
        if(!empty($list)){
            $list = $list->toArray();
            foreach($list as $v){
                $noticeId =  $v['id'];
                $silenceCycle = $v['silenceCycle'];
                $created_at = NotificationLog::where('notificationId','=',$noticeId)->orderBy('id', 'desc')->limit(1)->value('created_at');
                if(time()-strtotime($created_at)>$silenceCycle*60&&time()<=strtotime($v['stopNoticeTime'])){
                    $data = [
                        'hostId' => $v['hostId']??0,
                        'notificationSettingId' => $v['notificationSettingId'],
                        'notificationId' => $noticeId??0,
                        'notificationType' => $v['notificationType'],
                        'sendType' => $v['sendType'],
                        'ContactId' => $v['ContactId'],
                        'info' => $v['data'],
                        'sound_index' => $v['sound_index']??'',
                        'created_at' => date('Y-m-d H:i:s'),                    
                    ];
                    NotificationLog::insertGetId($data);
                }else{
                }
            }
        }
    }
    public static function insertSendInfo($params)
    {
        $type = $params['type']??1;
        $hostId = $params['hostId'];
        $noticeLogId = $params['noticeLogId']??0;
        $notificationId = $params['notificationId']??0;
        $notificationSettingId = $params['notificationSettingId']??0;
        $info = $params['info'];
        $status = $params['status']??0;
        $ContactId = $params['ContactId']??'';
        $sound_index = $params['sound_index']??'';
        if(empty($ContactId)){
            return  '';
        }
        $ContactIds = explode(',',$ContactId);
        $toSend = [];
        $person=Contact::whereIn('id',$ContactIds)->get();
        if(!empty($person)){
            $person = $person->toArray();
            $m=0;
            foreach($person as $vv){
                $toSend[$m]['id'] = $vv['id'];
                $toSend[$m]['email'] = $vv['email'];
                $toSend[$m]['phone'] = $vv['phone'];
                $m++;
            }
        }
        $sendData = [
            'hostId' => $hostId ?? 0,
            'notificationId' => $notificationId ?? 0,
            'notificationLogId' => $noticeLogId,
            'notificationSettingId' => $notificationSettingId,
            'sendTo' => '',
            'sound_index' => '',
            'type' => $type,
            'content' => $info,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s'),                    
        ];
        if($status==1){
            $sendData['updated_at'] = date('Y-m-d H:i:s');
        }
        $sendIds = [];
        if(in_array($type,[1,2])){
            //电话&&短信
            foreach($toSend as $v){
                $sendData['ContactId']  = $v['id'];
                $sendData['sendTo']  = $v['phone'];
                $sendData['status']  = 0;
                if($type==1){
                    $sendData['sound_index'] = $sound_index??'';
                }
                $rs = NotifiCation::getNoticeSendInfoCount($sendData);
                if($rs==0 || $status==1){
                    $sendIds[]=NotificationSendinfo::insertGetId($sendData);
                }
            }
            if(count($sendIds)==count($toSend)&&$status==0){
                $data = [
                    'status' => 2,
                    'updated_at' => date('Y-m-d H:i:s'), 
                ];
                NotificationLog::where('id','=',$noticeLogId)->update($data);
            }
        }elseif($type==3){
            //邮件
            foreach($toSend as $v){
                $sendData['ContactId']  = $v['id'];
                $sendData['sendTo']  = $v['email'];
                $sendData['status']  = 0;
                $rs = NotifiCation::getNoticeSendInfoCount($sendData);
                if($rs==0 || $status==1){
                    $sendIds[]=NotificationSendinfo::insertGetId($sendData);
                }
            }
            if(count($sendIds)==count($toSend)&&$status==0){
                $data = [
                    'status' => 2,
                    'updated_at' => date('Y-m-d H:i:s'), 
                ];
                NotificationLog::where('id','=',$noticeLogId)->update($data);
            }
        }else{
            //
        }

    }
    public static function updateNoticeStatus($params){
        $notificationSettingId = $params['notificationSettingId'] ?? 0;
        $relate_table = $params['relate_table'] ?? '';
        $relate_id = $params['relate_id'] ?? 0;
        if (!empty($relate_table)&&$relate_id>0) {
                $NotificationSettingInfo = NotificationSetting::where('id', '=', $notificationSettingId)->get();
                if (!empty($NotificationSettingInfo)) {
                    $NotificationSettingInfo = $NotificationSettingInfo->toArray();
                    if (!empty($NotificationSettingInfo)) {
                        $nstatus = $NotificationSettingInfo[0]['status'];
                        if ($nstatus != 1) {
                            self::updateNoticeStatusByRelateId($params);
                            return false;
                        } else {
                            $res['sendType'] = $NotificationSettingInfo[0]['sendType'];
                            $res['ContactId'] = $NotificationSettingInfo[0]['ContactId'];
                            $res['sound_index'] = $NotificationSettingInfo[0]['sound_index'];
                        }
                    } else {
                        self::updateNoticeStatusByRelateId($params);
                        return false;
                    }
                } else {
                    self::updateNoticeStatusByRelateId($params);
                    return false;
                }
                $res_ = $res ?? [];
            if ($relate_table == "url_info") {
                $urlInfo = UrlInfo::where('id', $relate_id)->get();
                if (!empty($urlInfo)) {
                    $urlInfo = $urlInfo->toArray();
                    if (!empty($urlInfo)) {
                        $nstatus = $urlInfo[0]['status'];
                        if ($nstatus != 1) {
                            self::updateNoticeStatusByRelateId($params);
                            return false;
                        } else {
                            if (!empty($res_)) {
                                return $res_;
                            } else {
                                return false;
                            }
                        }
                    } else {
                        self::updateNoticeStatusByRelateId($params);
                        return false;
                    }
                } else {
                    self::updateNoticeStatusByRelateId($params);
                    return false;
                }
            }elseif($relate_table == "snmp_host_role"){
                $snmpHostInfo = SnmpHostRole::where('id', $relate_id)->get();
                if (!empty($snmpHostInfo)) {
                    $snmpHostInfo = $snmpHostInfo->toArray();
                    if (empty($snmpHostInfo)) {
                        self::updateNoticeStatusByRelateId($params);
                        return false;                        
                    }
                }else{
                    self::updateNoticeStatusByRelateId($params);
                    return false;                        
                }                
            }
        } else {
            $NotificationSettingInfo = NotificationSetting::where('id', '=', $notificationSettingId)->get();
            if (!empty($NotificationSettingInfo)) {
                $NotificationSettingInfo = $NotificationSettingInfo->toArray();
                if (!empty($NotificationSettingInfo)) {
                    $nstatus = $NotificationSettingInfo[0]['status'];
                    if ($nstatus != 1) {
                        self::updateNoticeStatusBySettingId($notificationSettingId);
                        return false;
                    } else {
                        $res['sendType'] = $NotificationSettingInfo[0]['sendType'];
                        $res['ContactId'] = $NotificationSettingInfo[0]['ContactId'];
                        $res['sound_index'] = $NotificationSettingInfo[0]['sound_index'];
                        return $res;
                    }
                } else {
                    self::updateNoticeStatusBySettingId($notificationSettingId);
                    return false;
                }
            } else {
                self::updateNoticeStatusBySettingId($notificationSettingId);
                return false;
            }
        }
    }
    public static function updateNoticeStatusBySettingId($notificationSettingId)
    {
        $notificationSettingId = $notificationSettingId ?? 0;
        NotificationInfo::where('notificationSettingId', $notificationSettingId)->where('status', 0)->update(['status' => '-1']);
        NotificationLog::where('notificationSettingId', $notificationSettingId)->where('status', 1)->update(['status' => '-1']);
        NotificationSendinfo::where('notificationSettingId', $notificationSettingId)->where('status', 0)->update(['status' => '-1']);       
    }
    public static function updateNoticeStatusByRelateId($params)
    {
        $notificationSettingId = $params['notificationSettingId'] ?? 0;
        $relate_table = $params['relate_table'] ?? '';
        $relate_id = $params['relate_id'] ?? 0;
        $notificationList = NotificationInfo::where('relate_id', $relate_id)->where('relate_table', $relate_table)->where('status', 0)->get();
        NotificationInfo::where('relate_id', $relate_id)->where('relate_table', $relate_table)->where('status', 0)->update(['status' => '-1']);
        if(!empty($notificationList)){
            $notificationList = $notificationList->toArray();
            if(!empty($notificationList)){
                foreach($notificationList as $v){
                    $notificationId = $v['notificationId'];
                    NotificationLog::where('notificationId', $notificationId)->where('status', 1)->update(['status' => '-1']);
                    NotificationSendinfo::where('notificationId', $notificationId)->where('status', 0)->update(['status' => '-1']);
                }
            }
        }      
    }
    public static function SendWarningMsg($params)
    {
        //发送信息
        $list = NotificationSendinfo::where('status', 0)->orderBy('id', 'asc')->get();
        if (!empty($list)) {
            $list = $list->toArray();
            foreach ($list as $vv) {
                $id = $vv['id'];
                $ContactId = $vv['ContactId'];
                $hostId = $vv['hostId'];
                $type = $vv['type'];
                $ContactId = $vv['ContactId'];
                $sendTo = $vv['sendTo'];
                $content = $vv['content'];
                $sound_index = $vv['sound_index']??'';
                $created_at = $vv['created_at'];
                $notificationLogId = $vv['notificationLogId'];
                $notificationSettingId = $vv['notificationSettingId'];
                $notificationId = $vv['notificationId'];
                $NotificationSettingInfo = NotificationSetting::where('id',$notificationSettingId)->get();
                if (!empty($NotificationSettingInfo)) {
                    $NotificationSettingInfo = $NotificationSettingInfo->toArray();
                    if (!empty($NotificationSettingInfo)) {
                        $nstatus = $NotificationSettingInfo[0]['status'];
                        $sound_index = $NotificationSettingInfo[0]['sound_index'];
                        if ($nstatus != 1) {
                            NotificationLog::where('notificationSettingId', $notificationSettingId)->where('status', 1)->update(['status' => '-1']);
                            NotificationSendinfo::where('notificationSettingId', $notificationSettingId)->where('status', 0)->update(['status' => '-1']);
                            NotificationInfo::where('notificationSettingId', $notificationSettingId)->where('status', 0)->update(['status' => '-1']);
                            continue 1;
                        }
                    } else {
                        NotificationLog::where('notificationSettingId', $notificationSettingId)->where('status', 1)->update(['status' => '-1']);
                        NotificationSendinfo::where('notificationSettingId', $notificationSettingId)->where('status', 0)->update(['status' => '-1']);
                        NotificationInfo::where('notificationSettingId', $notificationSettingId)->where('status', 0)->update(['status' => '-1']);
                        continue 1;
                    }
                }else{
                    NotificationLog::where('notificationSettingId',$notificationSettingId)->where('status',1)->update(['status'=>'-1']);
                    NotificationSendinfo::where('notificationSettingId',$notificationSettingId)->where('status',0)->update(['status'=>'-1']);
                    NotificationInfo::where('notificationSettingId',$notificationSettingId)->where('status',0)->update(['status'=>'-1']);
                    continue 1;                 
                }
                if($notificationId>0){
                    $relate_id = NotificationInfo::where('id',$notificationId)->value('relate_id');
                }
                $params = [
                    'mobile' => $sendTo,
                    'content' => $content,
                    'sound_index' => $sound_index ?? '',
                ];
                if ($type == 1) {
                    $rs = NotificationRole::toTel($params);
                    //file_put_contents(storage_path('logs/jingu-eoms-semdsms.log'),$rs."\r\n",FILE_APPEND);
                }
                if ($type == 2) {
                    $rs = NotificationRole::toSms($params);
                    //file_put_contents(storage_path('logs/jingu-eoms-semdsms.log'),$rs."\r\n",FILE_APPEND);
                }
                if ($type == 3) {
                    $subject = '主机告警通知';
                    if($hostId==0){
                        $subject = '接口告警通知';
                        $type_ = "api";
                        $hostId = $relate_id??0;
                    }else{
                        $subject = '设备告警通知';
                        $type_ = "host";
                    }
                    NotificationRole::toEmail($ContactId,
                        $hostId,
                        [
                            "subject" => $subject,
                            "message" => $content,
                            "dateTime" => date("Y-m-d H:i:s"),
                            "type" => $type_??'',
                        ]
                    );
                }
                $data = [
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),

                    ];
                NotificationSendinfo::where('id', $id)->update($data);
            }
        }
        return true;
    }
}