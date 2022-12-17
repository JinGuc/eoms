<?php

namespace App\Http\Controllers\Api\v1\Home;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\NotificationInfo;
use App\Models\NotificationLog;
use App\Models\NotificationSetting;
use App\Models\SnmpHost;
use App\Models\UrlInfo;
use App\Models\SnmpHostRole;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function resourceOverview(Request $request)
    {
        $count = SnmpHost::where("status", 1)->count();
        $onlineCount = SnmpHost::where("status", 1)->where('running', 1)->count();
        $data["host"] = [
            "count"=>$count,
            "onlineCount"=>$onlineCount,
            "offlineCount"=>$count-$onlineCount,
            "errCount"=>NotificationLog::where("status", 1)->whereIn('notificationType',[1, 2, 3, 4, 5, 6])->groupBy('hostId')->count(),
        ];
        $count = SnmpHostRole::count();
        $onlineCount = SnmpHostRole::where('running', 1)->count();
        $data["server"] = [
            "count"=>$count,
            "onlineCount"=>$onlineCount,
            "offlineCount"=>$count-$onlineCount,
            "errCount"=>NotificationLog::where("status", 1)->whereNotIn('notificationType',[1, 2, 3, 4, 5, 6])->groupBy('hostId')->count(),
        ];
        $count = UrlInfo::count();
        $onlineCount = UrlInfo::where('running', 1)->count();
        $data["url"] = [
            "count"=>$count,
            "onlineCount"=>$onlineCount,
            "offlineCount"=>$count-$onlineCount,
            "errCount"=>NotificationInfo::where("status", 0)->where('notificationType',11)->groupBy('relate_id')->count(),
        ];
        $dataInfo = [
            "data"=>$data
        ];
        return ["status" => "success", "des" => "获取成功", "res" => $dataInfo];
    }

    public function resourceWarning(Request $request)
    {
        $count = NotificationLog::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("created_at", ">=", date("Y-m-d 23:59:59"))->count();
        $complete = NotificationLog::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("created_at", ">=", date("Y-m-d 23:59:59"))->where("status", "2")->count();
        $data["now"] = [
            "count"=>$count,
            "complete"=>$complete,
            "incomplete"=>$count-$complete,
        ];
        $count = NotificationLog::where("created_at", ">=", date("Y-m-d 00:00:00",time()-(60*60*24*7)))->where("created_at", ">=", date("Y-m-d 23:59:59"))->count();
        $complete = NotificationLog::where("created_at", ">=", date("Y-m-d 00:00:00",time()-(60*60*24*7)))->where("created_at", ">=", date("Y-m-d 23:59:59"))->where("status", "2")->count();
        $data["week"] = [
            "count"=>$count,
            "complete"=>$complete,
            "incomplete"=>$count-$complete,
        ];
        $count = NotificationLog::where("created_at", ">=", date("Y-m-d 00:00:00",time()-(60*60*24*30)))->where("created_at", ">=", date("Y-m-d 23:59:59"))->count();
        $complete = NotificationLog::where("created_at", ">=", date("Y-m-d 00:00:00",time()-(60*60*24*30)))->where("created_at", ">=", date("Y-m-d 23:59:59"))->where("status", "2")->count();
        $data["month"] = [
            "count"=>$count,
            "complete"=>$complete,
            "incomplete"=>$count-$complete,
        ];
        $dataInfo = [
            "data"=>$data
        ];
        return ["status" => "success", "des" => "获取成功", "res" => $dataInfo];
    }

    public function resourceList(Request $request)
    {
        $query = SnmpHost::query();

        $query->orderBy("updated_at", 'DESC');

        $HostList = $query->offset(0)
            ->limit(15)
            ->get(["id", "name", "host", "running", "type", "created_at", "updated_at"])
            ->toArray();

        if(count($HostList) > 0)
        {
            foreach($HostList as $key=>$host) {
                $last_server_info = false;
                if(is_array($host["last_server_info"])) {
                    if(count($host["last_server_info"])>0) {
                        $last_server_info = true;
                        $HostList[$key]["cpuUse"] = $host["last_server_info"]["cpu_use"];
                        $HostList[$key]["cpuLoad1"] = $host["last_server_info"]["cpu_load1"];
                        $HostList[$key]["cpuLoad5"] = $host["last_server_info"]["cpu_load5"];
                        $HostList[$key]["cpuLoad15"] = $host["last_server_info"]["cpu_load15"];
                        $HostList[$key]["memUsedPercent"] = $host["last_server_info"]["mem_used_percent"];
                        $HostList[$key]["netSpeed"] = $this->ifInfoFormat($host["last_server_info"]["net_speed"]);
                    }
                }
                if(!$last_server_info) {
                    $HostList[$key]["cpuUse"] = 0.00;
                    $HostList[$key]["cpuLoad1"] = 0.00;
                    $HostList[$key]["cpuLoad5"] = 0.00;
                    $HostList[$key]["cpuLoad15"] = 0.00;
                    $HostList[$key]["memUsedPercent"] = 0.00;
                    $HostList[$key]["netSpeed"] = ["in"=>["size"=>0,"format"=>"byte"],"out"=>["size"=>0,"format"=>"byte"]];
                }
                unset($HostList[$key]["last_server_info"]);
            }
            $resList = array(
                'data' => $HostList
            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }

    public function warningList(Request $request)
    {
        $query = NotificationLog::query();
        $query->orderBy("created_at", 'DESC');
        $NotificationLogList = $query->offset(0)
        ->limit(5);
        if($NotificationLogList->exists())
        {
            $NotificationLogList = $NotificationLogList->get(["id", "hostId as host", "notificationId as notification", "ContactId as Contact", "notificationType", "info", "status" ,"created_at", "updated_at"])
                ->toArray();
            foreach($NotificationLogList as $k=>$v)
            {
                if(mb_strlen($v["Contact"])>0)
                {
                    $ContactIds = explode(",", $v["Contact"]);
                    $ContactInfo = Contact::whereIn("id",$ContactIds)->get(["name"])->toArray();
                    $ContactInfoTmp = [];
                    foreach ($ContactInfo as $value)
                    {
                        $ContactInfoTmp[] = "[".$value["name"]."]";
                    }
                    $NotificationLogList[$k]["Contact"] = implode(",",$ContactInfoTmp);
                    unset($ContactInfoTmp);
                }
                if(mb_strlen($v["host"])>0)
                {
                    $notificationInfo = SnmpHost::find($v["host"]);
                    if($notificationInfo) {
                        $notificationInfo = $notificationInfo->toArray();
                        $NotificationLogList[$k]["host"] = array_key_exists("name", $notificationInfo)?$notificationInfo["name"]:"";
                        $NotificationLogList[$k]["hostIp"] = array_key_exists("host", $notificationInfo)?$notificationInfo["host"]:"";
                    }
                    else {
                        $NotificationLogList[$k]["host"] = "";
                        $NotificationLogList[$k]["hostIp"] = "";
                    }
                }
                else
                {
                    $NotificationLogList[$k]["host"] = "";
                    $NotificationLogList[$k]["hostIp"] = "";
                }
                if(mb_strlen($v["notification"])>0)
                {
                    $notificationInfo = NotificationSetting::find($v["notification"]);
                    if($notificationInfo) {
                        $notificationInfo = $notificationInfo->toArray();
                        $NotificationLogList[$k]["notification"] = array_key_exists("title", $notificationInfo)?$notificationInfo["title"]:"";
                    }
                    else {
                        $NotificationLogList[$k]["notification"] = "";
                    }
                }
            }
            $resList = array(
                'data' => $NotificationLogList,

            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }
}
