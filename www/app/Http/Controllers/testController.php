<?php

namespace App\Http\Controllers;

use App\Events\Snmp\HostEvent;
use App\Events\Snmp\ServerInfoEvent;
use App\Events\Snmp\SysWarningEvent;
use App\Models\ipList;
use App\Models\NotificationSetting;
use App\Models\NotificationInfo;
use App\Models\NotificationLog;
use App\Models\Contact;
use App\Models\ServerInfo;
use App\Servers\snmp\SnmpInfo;
use App\Servers\Snmp;
use App\Servers\NotifiCation;
use Illuminate\Http\Request;
use App\Events\Snmp\SysInfoEvent;
use phpseclib3\Net\SSH2;

class testController extends Controller
{
    //
    public function sysInfo(Request $request)
    {

        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => 1,
            "host" => "765765786",
            "type" => "ifInfo",
            "data" => [],
            "time" => time(),
        ]));
        event(new HostEvent([
        "status"=>"info",
        "host_id" => 1,
        "host" => "765765786",
        "type" => "ifInfo",
        "data" => [],
        "time" => time(),
    ]));
        return ['success'=>'1'];
    }
    public function snmpInfo(Request $request)
    {
        return SnmpInfo::get($request->input("host"),$request->input("name"));
    }
    public function ipList(Request $request)
    {
        $result = ipList::get()->toArray();
        $data = [];
        foreach($result as $value)
        {
            $data[] = ["start_ip"=>$value["start_ip"], "end_ip"=>$value["end_ip"], "ip"=>$value["ip"], "is_china"=>$value["is_china"], "country"=>$value["country"], "note"=>$value["note"]];
        }
        return $data;
    }
    //应用服务状态
    public function serviceStatus(Request $request){
        event(new ServerInfoEvent([
            "status" => "info",
            "host_id" => 3,
            "host" => "47.104.96.84",
            "type" => "status",
            "data" => ["running" => 1,"roleId"=>17],
            "time" => time(),
        ]));
        //$info = SnmpInfo::hServiceStatus('localhost',1,'nginx');
        // $info = SnmpInfo::hrSWRunNamepy('localhost');
        // return $info;
    }
    //应用服务操作
    public function serviceCtrl(Request $request){
        $info = SnmpInfo::hServiceCtrl('localhost',1,'nginx','ReStart');
        return $info;
    }
    //应用服务连接数
    public function serviceConnCount(Request $request){
        $info = SnmpInfo::hServiceConnCount('localhost',1,'nginx');
        return $info;
    }
    //告警
    public function noticeWarning(){
        $time = date('Y-m-d',strtotime('-30 days'));
        $time2 = date('Y-m-d H:i:s',time());
        $res = NotificationSetting::where('status','=',1)->get();
        if(!empty($res)){
            $res=$res->toArray();
            foreach($res as $v){
                $noticeSettingId = $v['id'];
                $type = $v['type'];
                $countType = $v['countType'];
                $cycle = $v['cycle'];
                $operator = $v['operator'];
                $value = $v['value'];
                $ContactId  = $v['ContactId'];
                $content = $v['content'];
                $sendType = $v['sendType'];
                $continueCycle = $v['continueCycle'];
                $silenceCycle = $v['silenceCycle'];
                $title = $v['title'];
                $ContactIds = explode(',',$ContactId);
                $time1 = date('Y-m-d H:i:s',time()-$cycle*60);
                $params['title'] = $title;
                $params['type'] = $type;
                $params['time1'] = $time1;
                $params['time2']  = $time2;
                $params['countType'] = $countType;
                $params['operator'] = $operator;
                $params['value'] = $value;
                $params['sendType'] = $sendType;
                $params['content'] = $content;
                $params['ContactId'] = $ContactId;
                $params['continueCycle'] = $continueCycle;
                $params['silenceCycle'] = $silenceCycle;
                $params['noticeSettingId'] = $noticeSettingId;
                NotifiCation::getwarningValue($params);
            }
        }
    }
    //获取防火墙
    public function iptablesRules()
    {
        //$info = SnmpInfo::hIptablesRules('localhost');
        //return $info;
        $list = Snmp::getHost();
        //for($time=0; $time<1; $time++) {
            foreach ($list as $k => $v) {
                $roles =  Snmp::getHostRole($v['id']);
                foreach ($roles as $kk => $vv) {
                        $server_id = $v['id'];
                        $device_ip = $v['host'];
                        $params['hostId'] = $server_id;
                        $params['roleId'] = $vv['id'];
                        $hRoleInfo = json_decode(SnmpInfo::hServiceStatus($device_ip, $server_id,$vv['type'])['info']??'',true)??[];
                        #print(type(hrswrunname))
                        $params['status'] = $hRoleInfo['status']??'';
                        $params['cpu_use'] = $hRoleInfo['cpuused']??0;
                        $params['memory_use'] = $hRoleInfo['memoryused']??0;
                        if(strpos($params['memory_use'],'G')!==false){
                            $params['memory_use']=substr($params['memory_use'],0,-1)*1024;
                        }else{
                            if(!empty($params['memory_use'])){
                                $params['memory_use']=substr($params['memory_use'],0,-1);
                            }else{
                                $params['memory_use']=0;
                            }
                        }
                        $params['memory_use'] = (string)$params['memory_use'];
                        $params['runtime'] = (string)($hRoleInfo['service_start_time']??'');
                        $hRoleConnCount = SnmpInfo::hServiceConnCount($device_ip, $server_id,$vv['type'])??[];
                        $params['connect_info']=$hRoleConnCount['info']??'{}';
                        Snmp::insertHostInfo($params);
                }
            }
    }
    public function iptablesNotify()
    {
        $data = file_get_contents("php://input");
        file_put_contents(storage_path('logs/jingu-eoms-iptables.log'),$data."\r\n",FILE_APPEND);
        //$data = $GLOBALS['HTTP_RAW_POST_DATA'];
        $res = json_decode($data,true);
        $rules_ = explode('@',$res['rules']);
        $rules = [];
        foreach($rules_ as $v){
            $rules[]=$v;
        }
        $ips = [];
        if (strpos($res['ip'], ',') !== false) {
            $ips = explode(',', $res['ip']);
        } else {
            $ips = [$res['ip']];
        }
        $host_id = 0;
        $list = Snmp::getHost();
        foreach ($list as $k => $v) {
            if (in_array($v['host'], $ips)) {
                $host_id = $v['id'];
                break;
            }
        }
        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $host_id,
            "host" => $res['ip'],
            "type" => "iptables",
            "data" => $rules,
            "time" => time(),
        ]));
    }
    public function snmpInfo2()
    {
        $list = Snmp::getHost();
        foreach($list AS $k=>$v){
            $server_id = $v['id'];
            $device_ip = $v['host'];
            $params['hostId'] = $server_id;
            $hSysInfo = SnmpInfo::hSysInfo($device_ip,$server_id);
            #print(type(hrswrunname))
            $params['hrswrunname'] = $hSysInfo['hrswrunname'];
            #获取操作系统信息
            $params['system_version'] = $hSysInfo['system_version'];
            $params['system_runtime'] = $hSysInfo['system_runtime'];
            $params['system_time'] = $hSysInfo['system_time'];
            #获取cpu使用率
            $hCpuInfo = SnmpInfo::hCpuInfo($device_ip,$server_id);
            $params['cpu_use'] = $hCpuInfo['cpu_use'];
            $params['cpu_load1'] = $hCpuInfo['cpu_load1'];
            $params['cpu_load5'] = $hCpuInfo['cpu_load5'];
            $params['cpu_load15'] = $hCpuInfo['cpu_load15'];
            $params['cpu_info'] = $hCpuInfo['cpu_info'];
            $params['cpu_core_num'] = $hCpuInfo['cpu_core_num'];
            #print(data)
            #获取内存信息
            $hMemInfo = SnmpInfo::hMemInfo($device_ip,$server_id);
            $params['memory_total'] = $hMemInfo['memory_total'] ;
            $params['memory_use'] = $hMemInfo['memory_use'] ;
            $params['mem_used_percent'] = $hMemInfo['mem_used_percent'] ;
            $params['today_login_error_totalCount'] = $hMemInfo['today_login_error_totalCount'] ;
            $hOpenPortInfo = SnmpInfo::hOpenPortInfo($device_ip,$server_id);
            $params['tcp_port'] = $hOpenPortInfo['tcp_port'];
            $params['udp_port'] = $hOpenPortInfo['udp_port'];
            #print(res)
            #获取TCP连接数
            $hTcpCountInfo = SnmpInfo::hTcpCountInfo($device_ip,$server_id);
            $params['tcp_connect_info'] = $hTcpCountInfo['tcp_connect_info'];
            #获取硬盘信息

            #diskinfo = np.array(diskinfo);
            $hDiskInfo = SnmpInfo::hDiskInfo($device_ip,$server_id);
            $params['disk_info'] = $hDiskInfo['disk_info'];;
            $hStorageInfo = SnmpInfo::hStorageInfo($device_ip,$server_id);
            $params['storage_info'] = $hStorageInfo['storage_info'];
            $params['disk_io'] = array_key_exists("diskio_info", $hDiskInfo) ? $hDiskInfo['diskio_info'] : "{}";

            #网络速度
            $ifInfo = SnmpInfo::ifInfo($device_ip, $server_id);
            $params["net_speed"] = array_key_exists("if_info", $ifInfo) ? $ifInfo['if_info'] : "{}";
            Snmp::insertServerInfo($params);
            $data[] = $params;  
        }
        echo json_encode($data,JSON_UNESCAPED_SLASHES);
        return;
    }

    public function warningEvent()
    {
        event(new SysWarningEvent([
            "status"=>"info",
            "host_id" => 3,
            "host" => "47.104.96.84",
            "type" => "storage",
            "data" => ["warning"=>true],
            "time" => time(),
        ]));
    }

    public function testSsh() {
        $host = new SSH2("47.104.96.84", 22);
        if($host == false) {
            return false;
        }
        if($host->login("root","{Jg@2022^&*}")) {
            return true;
        }
        return false;
    }

}
