<?php

namespace App\Http\Controllers\Api\v1\HostServer;

use App\Http\Controllers\Controller;
use App\Models\SnmpHost;
use App\Models\SnmpHostInfo;
use App\Models\SnmpHostRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HostServerController extends Controller
{
    protected $auth = [
        "username" => 'jinguc',
        "password" => 'jinguc_py_eoms!@#'
    ];
    protected $port = '888';
    //

    public function info(Request $request,$hostId)
    {
        $SnmpHostObj = SnmpHost::find($hostId);
        if(!$SnmpHostObj) {
            return ["status" => "fail", "des" => '未知的主机', 'res'=>[]];
        }
        $roleId = $request->get('roleId');
        $SnmpHostRoleObj = SnmpHostRole::where('id',$roleId)->where('hostId',$SnmpHostObj->id)->first();
        $SnmpHostRoleInfo = SnmpHostInfo::where('hostId',$SnmpHostObj->id)->where('roleId',$roleId)->orderBy('id','DESC')->first();
        if(!$SnmpHostRoleInfo || !$SnmpHostRoleObj) {
            return ["status" => "fail", "des" => '无数据', 'res'=>[]];
        }
        $connect_info = json_decode($SnmpHostRoleInfo->connect_info, true);
        $data = [
            "status" => (bool)$SnmpHostRoleObj->running,
            "cpu_use" => $SnmpHostRoleObj->running == 1 ? $SnmpHostRoleInfo->cpu_use : 0,
            "memory_use" => $SnmpHostRoleObj->running == 1 ? $SnmpHostRoleInfo->memory_use : 0,
            "runtime" => $SnmpHostRoleObj->running == 1 ? $SnmpHostRoleInfo->runtime : '',
            "connect" => $SnmpHostRoleObj->running == 1 ? (array_key_exists('CONNTOTAL',$connect_info) ? intval($connect_info["CONNTOTAL"]) : 0) : 0,
            "time" => $SnmpHostRoleObj->running == 1 ? strtotime($SnmpHostRoleInfo->created_at) : strtotime($SnmpHostRoleObj->updated_at)
        ];
        return ["status" => "success", "des" => '获取成功', 'res'=>["data"=>$data]];
    }
    public function elasticsearchinfo(Request $request,$hostId)
    {
        $SnmpHostObj = SnmpHost::find($hostId);
        if(!$SnmpHostObj) {
            return ["status" => "fail", "des" => '未知的主机', 'res'=>[]];
        }
        $roleId = $request->get('roleId');
        $SnmpHostRoleObj = SnmpHostRole::where('id',$roleId)->where('hostId',$SnmpHostObj->id)->first();
        $SnmpHostRoleInfo = SnmpHostInfo::where('hostId',$SnmpHostObj->id)->where('roleId',$roleId)->orderBy('id','DESC')->first();
        if(!$SnmpHostRoleInfo || !$SnmpHostRoleObj) {
            return ["status" => "fail", "des" => '无数据', 'res'=>[]];
        }
        //$SnmpHostRoleInfo->es_health_info = file_get_contents(storage_path('es.txt'));
        $es_health_info = json_decode($SnmpHostRoleInfo->es_health_info, true);
        $status = $es_health_info['colony']['status']??'';
        $indices = $es_health_info['indices']['indices'];
        $shards_indices = $es_health_info['shards']['indices'];
        $state_indices = $es_health_info['state']['routing_table']['indices'];
        $m=0;
        foreach($indices as $k=>$v){
            $indices_[$m]['name'] = $k;
            $indices_[$m]['info'] = $v;
            $m++;
        }
        unset($es_health_info['indices']);
        $es_health_info['indices'] = $indices_;
        $m=0;
        foreach($shards_indices as $k=>$v){
            $indices_[$m]['name'] = $k;
            $indices_[$m]['info'] = $v;
            $m++;
        }
        unset($es_health_info['shards']['indices']);
        $es_health_info['shards']['indices'] = $indices_;
        $recovery = $es_health_info['recovery'];
        $m=0;
        $mm=0;
        foreach($recovery as $k=>$v){
            $shards_[$m]['name'] = $k;
            $shards_[$m]['unassigned'] = [];
            $target_name=[];
            foreach($v['shards'] as $kk=>$vv){
                if(!empty($vv['target']['name']??'')){
                    $target_name[$vv['target']['name']][]=['id'=>$vv['id'],'primary'=>$vv['primary'],'info'=>$state_indices[$k]['shards'][$vv['id']]];
                }
            }
            $n=0;
            foreach($target_name as $kk=>$vv){
                $target_name_[$n]['name'] = $kk;
                $vv = sortArrByManyField($vv,'primary',SORT_DESC,'id',SORT_ASC);
                $target_name_[$n]['list'] = $vv; 
                $n++;
            }
            $target_name_ = sortArrByManyField($target_name_,'name',SORT_ASC);
            $shards_[$m]['nodes'] = $target_name_;
            $m++;
        }
        
        unset($es_health_info['recovery']);
        $shards_ = sortArrByManyField($shards_,'name',SORT_ASC);
        $es_health_info['recovery']['shards'] = $shards_;
        unset($es_health_info['state']);
        $data = [
            "status" => !empty($status)?true:false,
            "run_status" => $es_health_info['colony']['status']??'',
            "cpu_use" => $SnmpHostRoleObj->running == 1 ? $SnmpHostRoleInfo->cpu_use : 0,
            "memory_use" => $SnmpHostRoleObj->running == 1 ? $SnmpHostRoleInfo->memory_use : 0,
            "runtime" => $SnmpHostRoleObj->running == 1 ? $SnmpHostRoleInfo->runtime : '',
            'number_of_nodes' => $es_health_info['colony']['number_of_nodes']??0,
            "es_health_info" => $SnmpHostRoleObj->running == 1 ? $es_health_info:[],
            "time" => $SnmpHostRoleObj->running == 1 ? strtotime($SnmpHostRoleInfo->created_at) : strtotime($SnmpHostRoleObj->updated_at)
        ];
        return ["status" => "success", "des" => '获取成功', 'res'=>["data"=>$data]];
    }
    public function EChartList(Request $request,$hostId){
        $roleId = $request->get('roleId');
        $type = $request->input('type', "1"); // 1cpu使用率 2内存使用率 3负载 4tcp连接数
        $dateType = $request->input('dateType', 1); //1分钟 2小时 3天 4月 5年
        $startTime = $request->input('startTime') ?? date('Y-m-d H:i:s',time()-600);
        $endTime = $request->input('endTime') ?? date('Y-m-d H:i:s');
        if(!$dateType) {
            $diffTime = strtotime($endTime) - strtotime($startTime);
            var_dump($diffTime);
            if($diffTime<=60) {
                $dateType = 6;
            } elseif($diffTime<=(60*60)) {
                $dateType = 1;
            } elseif($diffTime<=(60*60*24)) {
                $dateType = 2;
            } elseif($diffTime<=(60*60*24*30)) {
                $dateType = 3;
            } elseif($diffTime<=(60*60*24*30*12)) {
                $dateType = 4;
            } elseif($diffTime>(60*60*24*30*12)) {
                $dateType = 5;
            } else {
                $dateType = 1;
            }
        }
        $SnmpHostInfoObj = SnmpHostInfo::query();
        $SnmpHostInfoObj->where("hostId", $hostId)
            ->where("roleId", $roleId)
            ->where("created_at",">=",$startTime)
            ->where("created_at","<=",$endTime)
            ->orderBy('created_at',"DESC");
        switch ($type) {
            case "1": //cpu使用率
                $SnmpHostInfoList = $SnmpHostInfoObj->get(["cpu_use","created_at"])->toArray();
                $data = $this->arrayToEchartsByDate($SnmpHostInfoList, ["cpu_use"], $dateType);
                $data["y"] = ["cpu使用率"];
                $data["x"] = "时间";
                break;
            case "2": //内存使用率
                $SnmpHostInfoList = $SnmpHostInfoObj->get(["memory_use","created_at"])->toArray();
                $data = $this->arrayToEchartsByDate($SnmpHostInfoList, ["memory_use"], $dateType);
                $data["y"] = ["内存使用率"];
                $data["x"] = "时间";
                break;
            case "3": //连接数
                $SnmpHostInfoList = $SnmpHostInfoObj->get(["connect_info","created_at"])->toArray();
                $data = $this->arrayToEchartsByDate($SnmpHostInfoList, ["connect_info"], $dateType);
                $data["y"] = ["连接数"];
                $data["x"] = "时间";
                break;
            default:
                $SnmpHostInfoList = [];
                $data = [];
        }
        if(count($SnmpHostInfoList)>0)
        {
            if(count($data)>0) {
                $data["updated_at"] = date("Y-m-d H:i:s");
                return ["status"=>"success","des"=>"获取成功","res"=>$data];
            }
            return ["status"=>"success","des"=>"无数据","res"=>[]];
        }
        return ["status"=>"success","des"=>"无数据","res"=>[]];
    }

    public function getConfigDir(Request $request,$hostId)
    {
        $server = $request->get('server');
        if(!in_array($server,['mysql','nginx','httpd','ipcc','redis','php','php-fpm','snmp','docker'])) {
            return ["status" => "fail", "des" => '未知的服务', 'res'=>[]];
        }
        $SnmpHostObj = SnmpHost::find($hostId);
        if(!$SnmpHostObj) {
            return ["status" => "fail", "des" => '未知的主机', 'res'=>[]];
        }
        $response = Http::withBasicAuth($this->auth['username'],$this->auth['password'])->asForm()->post('http://'.$SnmpHostObj->host.':'.$this->port.'/service/config/dir',[
            "service_name"=>$server=="httpd"?"apache":$server,
        ]);
        if($response->status() == 200) {
            $result = $response->json();
            return ['status'=>'success','des'=>'操作成功','res'=>["data"=>$result['msg']]];
        }
        return ['status'=>'fail','des'=>'操作失败','res'=>[]];
    }

    public function getConfig(Request $request,$hostId)
    {
        $server = $request->get('server');
        if(!in_array($server,['mysql','nginx','httpd','ipcc','redis','php','php-fpm','snmp','docker'])) {
            return ["status" => "fail", "des" => '未知的服务', 'res'=>[]];
        }
        $SnmpHostObj = SnmpHost::find($hostId);
        if(!$SnmpHostObj) {
            return ["status" => "fail", "des" => '未知的主机', 'res'=>[]];
        }
        $response = Http::withBasicAuth($this->auth['username'],$this->auth['password'])->asForm()->post('http://'.$SnmpHostObj->host.':'.$this->port.'/service/config',[
            "service_name"=>$server=="httpd"?"apache":$server,
        ]);
        if($response->status() == 200) {
            $result = $response->json();
            return ['status'=>'success','des'=>'操作成功','res'=>["data"=>$result['msg']]];
        }
        return ['status'=>'fail','des'=>'操作失败','res'=>[]];
    }

    public function getLog(Request $request,$hostId)
    {
        $server = $request->get('server');
        if(!in_array($server,['mysql','nginx','httpd','ipcc','redis','php','php-fpm','snmp','docker'])) {
            return ["status" => "fail", "des" => '未知的服务', 'res'=>[]];
        }
        $SnmpHostObj = SnmpHost::find($hostId);
        if(!$SnmpHostObj) {
            return ["status" => "fail", "des" => '未知的主机', 'res'=>[]];
        }
        $response = Http::withBasicAuth($this->auth['username'],$this->auth['password'])->asForm()->post('http://'.$SnmpHostObj->host.':'.$this->port.'/service/chunk/log',
        [
            "start"=>$request->get('start', 1),
            "service_name"=>$server=="httpd"?"apache":$server,
            "fileName"=>$request->get('fileName')
        ]);
        if($response->status() == 200) {
            $result = $response->json();
            return ['status'=>'success','des'=>'获取成功','res'=>["data"=>$result['msg']]];
        }
        return ['status'=>'fail','des'=>'获取失败','res'=>[]];
    }

    public function getLogDir(Request $request,$hostId)
    {
        $server = $request->get('server');
        if(!in_array($server,['mysql','nginx','httpd','ipcc','redis','php','php-fpm','snmp','docker'])) {
            return ["status" => "fail", "des" => '未知的服务', 'res'=>[]];
        }
        $SnmpHostObj = SnmpHost::find($hostId);
        if(!$SnmpHostObj) {
            return ["status" => "fail", "des" => '未知的主机', 'res'=>[]];
        }
        $response = Http::withBasicAuth($this->auth['username'],$this->auth['password'])->asForm()->post('http://'.$SnmpHostObj->host.':'.$this->port.'/service/log/dir',
        [
            "service_name"=>$server=="httpd"?"apache":$server,
        ]);
        if($response->status() == 200) {
            $result = $response->json();
            if($result["status"]) {
                return ['status'=>'success','des'=>'获取成功','res'=>["data"=>$result["msg"]]];
            }
            return ['status'=>'fail','des'=>$result["des"] ?? '获取失败','res'=>["data"=>[]]];
        }
        return ['status'=>'fail','des'=>'获取失败','res'=>[]];

    }

    public function getPyServerData(Request $request,$hostId)
    {
        $SnmpHostObj = SnmpHost::find($hostId);
        if(!$SnmpHostObj) {
            return ["status" => "fail", "des" => '未知的主机', 'res'=>[]];
        }
        $uri = $request->get('uri');
        if(!in_array($uri,['/iptables/get_status','/iptables/set_status','/iptables/get_rules_list','/iptables/create_rules','/iptables/modify_rules','/iptables/remove_rules','/iptables/get_ip_rules_list','/iptables/create_ip_rules','/iptables/modify_ip_rules','/iptables/remove_ip_rules','/iptables/remove_all_ip_rules'])) {
            return ["status" => "fail", "des" => '无效的uri', 'res'=>[]];
        }
        $response = Http::withBasicAuth($this->auth['username'],$this->auth['password'])->asForm()->post('http://'.$SnmpHostObj->host.':'.$this->port.'/'.$uri,
            $request->except(['uri']));

        if($response->status() == 200) {
            $result = $response->json();
            return ['status'=>'success','des'=>'操作成功','res'=>["data"=>$result['msg']]];
        }
        return ['status'=>'fail','des'=>'操作失败','res'=>[]];
    }

    public function serverOperation(Request $request,$hostId)
    {
        $SnmpHostObj = SnmpHost::find($hostId);
        if(!$SnmpHostObj) {
            return ["status" => "fail", "des" => '未知的主机', 'res'=>[]];
        }
        $uri = "/service_".$request->get('act');
        $service = $request->get('service');
        if(empty($service)) {
            return ["status" => "fail", "des" => '服务名必填', 'res'=>[]];
        }
        if(!in_array($uri,['/service_status','/service_start','/service_stop','/service_restart','/service_reload'])) {
            return ["status" => "fail", "des" => '无效的动作，支持【start、stop、status、restart、reload】', 'res'=>[]];
        }
        $response = Http::withBasicAuth($this->auth['username'],$this->auth['password'])->asForm()->post('http://'.$SnmpHostObj->host.':'.$this->port.'/'.$uri,["service_name"=>$service]);

        if($response->status() == 200) {
            $result = $response->json();
            if($result["state"]=="success") {
                return ['status'=>'success','des'=>'操作成功','res'=>["data"=>$result["res"]]];
            }
            return ['status'=>'fail','des'=>$result["des"] ?? '操作失败','res'=>["data"=>$result["res"]]];
        }
        return ['status'=>'fail','des'=>'操作失败','res'=>[]];
    }
}
