<?php

namespace App\Console\Commands;

use App\Events\Snmp\ServerInfoEvent;
use App\Models\SnmpHostRole;
use App\Models\SnmpRole;
use App\Models\WebSetting;
use App\Servers\Snmp;
use App\Servers\snmp\SnmpInfo;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RoleInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snmp:roleInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取系统服务信息';

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
        $list = Snmp::getHost();
        //for($time=0; $time<1; $time++) {
            foreach ($list as $k => $v) {
                $roles =  Snmp::getHostRole($v['id']);
                foreach ($roles as $kk => $vv) {
                    try {
                        $server_id = $v['id'];
                        $device_ip = $v['host'];
                        $params['hostId'] = $server_id;
                        $params['roleId'] = $vv['id'];
                        $rdata = [];
                        if ($vv['type'] == 'elasticsearch') {
                            $port = 0;
                            $akey = '';
                            $opt = [];
                            $SnmpRoleInfo = SnmpRole::where('key', '=', 'elasticsearch')->get();
                            if(!empty($SnmpRoleInfo)){
                                $SnmpRoleInfo = $SnmpRoleInfo->toArray();
                                if(!empty($SnmpRoleInfo)){
                                    $port = $SnmpRoleInfo[0]['port'];
                                    $user = $SnmpRoleInfo[0]['user'];
                                    $password = $SnmpRoleInfo[0]['password'];
                                }
                                if(!empty($user)&&!empty($password)){
                                    $akey = base64_encode($user.':'.$password);
                                    $opt['header'] = [
                                        'Authorization:Basic '.$akey
                                    ];
                                }
                            }
                            if ($port > 0) {
                                //集群
                                $url = "http://" . $device_ip . ":" . $port . "/_cluster/health";
                                $result = request_by_curl($url,'',$opt);
                                //Log::debug("获取主机elasticsearch", ["result" => $result]);
                                $sArr = json_decode($result??'', true);
                                $hRoleInfo['status'] = $params['status'] = '';
                                $params['cpu_use'] = 0;
                                $params['memory_use'] = 0;
                                $params['connect_info'] = '{}';
                                $params['es_health_info'] = '{}';
                                $params['runtime'] = '1900-01-01';
                                if (!empty($sArr['cluster_name'] ?? '')||!empty($result)) {
                                    $rdata['colony'] = json_decode($result??'',true);
                                    //索引
                                    $url = "http://" . $device_ip . ":" . $port . "/_cluster/health?pretty&level=indices";
                                    $result_index = request_by_curl($url,'',$opt);
                                    $rdata['indices'] = json_decode($result_index??'',true);
                                    //分片
                                    $url = "http://" . $device_ip . ":" . $port . "/_cluster/health?pretty&level=shards";
                                    $result_shards = request_by_curl($url,'',$opt);
                                    $rdata['shards'] = json_decode($result_shards??'',true);
                                    //恢复
                                    $url = "http://" . $device_ip . ":" . $port . "/_recovery?pretty";
                                    $result_recovery = request_by_curl($url,'',$opt);
                                    $rdata['recovery'] = json_decode($result_recovery??'',true);
                                    //节点信息
                                    $url = "http://" . $device_ip . ":" . $port . "/_cluster/state";
                                    $result_recovery = request_by_curl($url,'',$opt);
                                    $rdata['state'] = json_decode($result_recovery??'',true);

                                    $params['es_health_info'] = json_encode($rdata);
                                    if($sArr['status']=='red'){
                                        $hRoleInfo['status'] = $params['status'] = '';
                                    }else{
                                        $hRoleInfo['status'] = $params['status'] = 'running';
                                    }
                                }
                                Snmp::insertHostInfo($params);
                            }
                        } else {
                            $params['es_health_info'] = '{}';
                            $hRoleInfo = json_decode(SnmpInfo::hServiceStatus($device_ip, $server_id, $vv['type'])['info'] ?? '', true) ?? [];
                            $params['status'] = $hRoleInfo['status'] ?? '';
                            $params['cpu_use'] = $hRoleInfo['cpuused'] ?? 0;
                            $params['memory_use'] = $hRoleInfo['memUsedPercent'] ?? 0;
                            $hRoleInfo['service_start_time'] = $hRoleInfo['service_start_time'] ?? '';
                            if ($hRoleInfo['service_start_time'] == '') {
                                $hRoleInfo['service_start_time'] = '1900-01-01';
                            }
                            if (strpos($params['memory_use'], 'G') !== false) {
                                $params['memory_use'] = substr($params['memory_use'], 0, -1) * 1024;
                            } else {
                                if (!empty($params['memory_use'])) {
                                    $params['memory_use'] = substr($params['memory_use'], 0, -1);
                                } else {
                                    $params['memory_use'] = 0;
                                }
                            }
                            $params['runtime'] = (string)($hRoleInfo['service_start_time'] ?? '1900-01-01');
                            $hRoleConnCount = SnmpInfo::hServiceConnCount($device_ip, $server_id, $vv['type']) ?? [];
                            $params['connect_info'] = $hRoleConnCount['info'] ?? '{}';
                            Snmp::insertHostInfo($params);
                        }
                        if (array_key_exists("status", $hRoleInfo) && $hRoleInfo['status'] == "running") {
                            if ($vv["running"] == 0) {
                                event(new ServerInfoEvent([
                                    "status" => "info",
                                    "host_id" => $v["id"],
                                    "host" => $v["host"],
                                    "type" => "status",
                                    "data" => ["running" => 1,"roleId"=>$vv['id'],'rdata'=>$rdata],
                                    "time" => time(),
                                ]));
                            }
                            SnmpHostRole::find($vv["id"])->update(["running" => "1", "heartNum" => 1]);
                        } else {
                            if ($vv["running"] == 1) {
                                event(new ServerInfoEvent([
                                    "status" => "info",
                                    "host_id" => $v["id"],
                                    "host" => $v["host"],
                                    "type" => "status",
                                    "data" => ["running" => 0,"roleId"=>$vv['id'],'rdata'=>$rdata],
                                    "time" => time(),
                                ]));
                            }
                            SnmpHostRole::find($vv["id"])->update(["running" => "0", "heartNum" => 0]);
                        }
                    }
                    catch (Exception $e) {
                        Log::error("获取主机服务信息失败", ["id" => $v["id"], "name" => $v["name"], "ip" => $v["host"], 'role'=>$vv['type'], "message" => $e->getMessage() . ' at File ' . $e->getFile() . ' in Line ' . $e->getLine()]);
                        if ($vv["heartNum"] > $serverHeart) {
                            if($vv["running"]==1) {
                                event(new ServerInfoEvent([
                                    "status" => "info",
                                    "host_id" => $v["id"],
                                    "host" => $v["host"],
                                    "type" => "status",
                                    "data" => ["running" => 0,"roleId"=>$vv['id']],
                                    "time" => time(),
                                ]));
                            }
                            SnmpHostRole::find($vv["id"])->update(["running" => "0", "heartNum" => 0]);
                        } else {
                            SnmpHostRole::find($vv["id"])->update(["heartNum" => $vv["heartNum"] + 1]);
                        }
                    }
                }
            }
        //sleep(60);
        //}
        //return 0;
    }
}
