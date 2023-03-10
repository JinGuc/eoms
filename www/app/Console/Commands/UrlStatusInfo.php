<?php

namespace App\Console\Commands;

use App\Events\Snmp\ServerInfoEvent;
use App\Models\UrlStatusInfo  as UrlStatusDetail;
use App\Models\UrlInfo;
use App\Models\WebSetting;
use App\Servers\Snmp;
use App\Servers\NotifiCation;
use App\Models\NotificationSetting;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UrlStatusInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snmp:UrlStatusInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取URL接口状态';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $serverHeart = WebSetting::where("fd_name","server_heartbeat")->first();
        if(!$serverHeart) {
            $serverHeart = 5;
        }
        else {
            $serverHeart = $serverHeart->value;
        }
        $list = [];
        $list = UrlInfo::where('status',1)->get();
        if(!empty($list)){
            $list = $list->toArray();
        }
        $time = date('Y-m-d H:i:s');
        $status_code = '';
        //for($time=0; $time<1; $time++) {
        foreach ($list as $k => $v) {
            try {
                $id = $v['id'];
                $url = $v['url'];
                $url = str_replace(['https://', 'http://'], '', $url);
                $port = $v['port']??0;
                $title = $v['title']??'';
                $db_username = $v['db_username']??'';
                $db_password = $v['db_password']??'';
                $timeout = $v['timeout'] ?? 60;
                $http_type = strtolower($v['type']);
                $status_msg = '';
                if ($http_type == 'mysql') {
                    $starttime = explode(' ', microtime()) ?? [];
                    // 创建连接
                    try {
                        if (!empty($port)) {
                            $con = mysqli_connect($url, $db_username, $db_password, "", $port);
                        } else {
                            $con = mysqli_connect($url, $db_username, $db_password);
                        }
                        // 检查连接
                        if (!$con) {
                            $status_msg = mysqli_connect_error();
                            UrlInfo::find($id)->update(["running" => 0, 'response_time' => '-', 'status_msg' => $status_msg, 'gathering_time' => $time]);
                        } else {
                            $endtime = explode(' ', microtime()) ?? [];
                            if (!empty($starttime) && !empty($endtime)) {
                                $thistime = $endtime[0] + $endtime[1] - ($starttime[0] + $starttime[1]);
                                $thistime = round($thistime, 3);
                            } else {
                                $thistime = '-';
                            }
                            UrlInfo::find($id)->update(["running" => 1, 'response_time' => $thistime, 'status_msg' => $status_msg, 'gathering_time' => $time]);
                        }
                    } catch (Exception $e) {
                        $status_msg = $e->getMessage();
                        UrlInfo::find($id)->update(["running" => 0, 'response_time' => '-','status_msg'=>$status_msg, 'gathering_time' => $time]);
                    }
                    if (!empty($status_msg)) {
                        $params['url_id'] = $id;
                        $params['url'] = $url;
                        $params['url_title'] = $title;
                        $msg = explode(': ',$status_msg);
                        $msg_ = str_replace(['(',')'],'',$msg[1]??'');
                        $msg__ = explode('/',$msg_??'');
                        if(is_array($msg__)&&count($msg__)>0){
                            $status_code = $msg__[1]??intval($msg__[0]??'');
                        }else{
                            $status_code = 0;
                        }
                        $http_code = $status_code??0;
                        $notice_ok = 0;
                        $params['status_code'] = $status_code;
                        Snmp::insertUrlStatusInfo($params);
                    }else{
                        $http_code = 200;
                        $notice_ok = 1;
                    }

                } else {
                    if (substr($url, -1) == '/') {
                        $url = substr($url, 0, -1);
                    }
                    if (strpos($url, ':') !== false) {
                        $port = '';
                    }
                    if (!empty($url)) {
                        $url_ = $http_type . '://' . $url . ':' . $port;
                        if (empty($port)) {
                            $url_ = $http_type . '://' . $url;
                        }
                        $starttime = explode(' ', microtime()) ?? [];
                        $http_code = curl_get_url_http_code($url_, $timeout);
                        $endtime = explode(' ', microtime()) ?? [];
                        if (empty($http_code) || $http_code >= 500) {
                            $params['url_id'] = $id;
                            $params['url'] = $url_;
                            $params['url_title'] = $title;
                            $params['status_code'] = intval($http_code);
                            Snmp::insertUrlStatusInfo($params);
                            UrlInfo::find($id)->update(["running" => 0, 'response_time' => '-', 'gathering_time' => $time]);
                            $notice_ok = 0;
                        } else {
                           $notice_ok = 1;
                        }
                    }
                }
                if ((!empty($http_code) && $http_code < 500 && $notice_ok == 1)) {
                    if (!empty($starttime) && !empty($endtime)) {
                        $thistime = $endtime[0] + $endtime[1] - ($starttime[0] + $starttime[1]);
                        $thistime = round($thistime, 3);
                    } else {
                        $thistime = '-';
                    }
                    UrlInfo::find($id)->update(["running" => 1, 'response_time' => $thistime, 'gathering_time' => $time]);
                    $notificationInfo = [];
                    $NotificationSettingResult = NotificationSetting::where("type", 11)->where("status", 1)->get();
                    if (!empty($NotificationSettingResult)) {
                        $NotificationSettingResult = $NotificationSettingResult->toArray();
                        if (!empty($NotificationSettingResult)) {
                            $notificationInfo = $NotificationSettingResult[0];
                            $notificationSettingId = $notificationInfo['id'];
                            $nstatus = $notificationInfo['status'];
                        }
                    } else {
                        return false;
                    }
                    $url = $url_ ?? '';
                    $url_title = $title ?? '';
                    $title = $notificationInfo['title'] ?? '';
                    $type = $notificationInfo['type'] ?? '';
                    $countType = $notificationInfo['countType'] ?? 1;
                    $operator = $notificationInfo['operator'] ?? 1;
                    $value = $notificationInfo['value'] ?? 0;
                    $sendType = $notificationInfo['sendType'] ?? 1;
                    $content = $notificationInfo['content'] ?? '';
                    $ContactId = $notificationInfo['ContactId'] ?? '';
                    $continueCycle  = $notificationInfo['continueCycle'] ?? 10;
                    $silenceCycle = $notificationInfo['silenceCycle'] ?? 60;
                    $noticeSettingId = $notificationInfo['id'] ?? 0;
                    $sound_index = $notificationInfo['sound_index'] ?? '';
                    $now_value = 1;
                    $params_ = [
                        'type' => $type,
                        'operator' => $operator,
                        'value' => $value,
                        'now_value' => $now_value,
                        'sendType' => $sendType,
                        'content' => $url_title .','. $title.',恢复正常',
                        'hostId' => 0,
                        'host' => '',
                        'relate_table' => 'url_info',
                        'relate_id' => $id ?? 0,
                        'ContactId' => $ContactId,
                        'continueCycle' => $continueCycle,
                        'silenceCycle' => $silenceCycle,
                        'noticeSettingId' => $noticeSettingId,
                        'sound_index' => $sound_index,
                        'status' => 1,
                    ];
                    //Log::debug("检查主机内存告警1", ["hostId" => $server_id, "data" => $params]);
                    $server = new NotifiCation();
                    $res = $server->warningInfo($params_);
                }
            } catch (Exception $e) {
                Log::error("获取监控接口状态失败", ["id" => $v["id"], "url" => $v["url"], "message" => $e->getMessage() . ' at File ' . $e->getFile() . ' in Line ' . $e->getLine()]);
            }
        }
        //sleep(60);
        //}
        //return 0;
    }
}
