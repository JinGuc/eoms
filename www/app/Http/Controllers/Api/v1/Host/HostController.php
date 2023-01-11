<?php

namespace App\Http\Controllers\Api\v1\Host;

use App\Events\Snmp\HostEvent;
use App\Http\Controllers\Controller;
use App\Models\HostNotificationSetting;
use App\Models\ServerInfo;
use App\Models\SnmpHost;
use App\Models\SnmpHostRole;
use App\Models\SnmpRole;
use App\Servers\Tools\Rsa;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HostController extends Controller
{
    /**
     * 服务器列表
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        Log::info("获取服务器列表信息", ["request" => $request]);
        $page = $request->input('page', 1);
        $limit = $request->input('pagesize', 20);

        $query = SnmpHost::query();

        if($request->input('status')) $query->where('status', $request->input('status'));
        if($request->input('keywords')) $query->where(function($query) use ($request) { return $query->where('name' ,'like', "%".$request->input('keywords')."%")->orWhere('host', $request->input('keywords'));});

        $query->orderBy("created_at", 'DESC');
        $total = $query->count();


        $HostList = $query->offset(($page - 1) * $limit)
            ->limit($limit);


        if($HostList->exists())
        {
            $HostList = $HostList->get(["id", "name", "host", "status", "running", "type", "created_at", "updated_at"])
            ->toArray();
            $resList = array(
                'data' => $HostList,
                'total' => $total

            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }

    /**
     * 服务器信息详情
     * @param Request $request
     * @return array
     */
    public function info(Request $request)
    {
        Log::info("服务器配置详情获取", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $SnmpHostObj = SnmpHost::with(["NotificationSettingId","role"])->find($request->input('id'));
        if($SnmpHostObj){
            $SnmpHostObj = $SnmpHostObj->toArray();
            if(mb_strlen($SnmpHostObj["username"])>0)
            {
                $SnmpHostObj["username"] = Crypt::encryptString($SnmpHostObj["username"]);
            }
            if(mb_strlen($SnmpHostObj["password"])>0)
            {
                $SnmpHostObj["password"] = Crypt::encryptString($SnmpHostObj["password"]);
            }
            $role = [];
            foreach($SnmpHostObj["role"] as $SnmpHostRole)
            {
                $role[] = $SnmpHostRole["type"];
            }
            $SnmpHostObj["role"] = implode(",", $role);
            $NotificationSettingId = [];
            foreach($SnmpHostObj["notification_setting_id"] as $SnmpHostNotificationSetting)
            {
                $NotificationSettingId[] = $SnmpHostNotificationSetting["notificationId"];
            }
            $SnmpHostObj["NotificationSettingId"] = implode(",", $NotificationSettingId);
            return ["status" => "success", "des" => '获取成功', 'res'=>["data"=>$SnmpHostObj]];
        }
        return ["status" => "fail", "des" => '获取失败', 'res'=>[]];

    }

    /**
     * 服务器添加或修改
     * @param Request $request
     * @return array|string[]
     */
    public function addOrEdit(Request $request)
    {
        Log::info("添加或修改服务器信息", ["request" => $request]);
        $act = $request->input('act','');//当前动作，表示想做另外的事
        $id = $request->input('id','');//传了为编辑，未传为新增
        $SnmpHostObj = $id ? SnmpHost::find($id) : new SnmpHost;
        if($id && empty($SnmpHostObj)){
            return ["status" => "fail", "des" => '未知的对象', "res"=>[]];
        }
        if($act == 'changeStatus'){//该动作表示只更新状态字段
            if(!$id){
                return ["status" => "fail", "des" => '无效的id'];
            }
            $SnmpHostObj->status = $request->input('status',SnmpHost::$_status['on']['code']);

        }
        else{
            $validate = Validator::make($request->all(),[
                "host"=>[
                    "required",
                    "ip",
                ],
                "name"=>"required",
                "type"=>"required",
            ],[
                "host.required"=>"ip地址不能为空",
                "host.ip"=>"ip地址非法",
                "name.required"=>"主机名称不能为空",
                "type.required"=>"主机类型不能为空",
            ]);
            if($validate->fails()){
                return ["status" => "fail", "des" => $validate->errors()->first(), 'res'=>[]];
            }
            $SnmpHostExists = SnmpHost::where('host',$request->input('host'))
                ->when($id, function($query, $id){
                    return $query->where('id', '!=',$id);
                });
            if($SnmpHostExists->exists())
            {
                return ["status" => "fail", "des" => '已存在ip地址为:['.$request->input('host').']的主机', 'res'=>[]];
            }
            $SnmpHostObj->name = $request->input('name');
            $SnmpHostObj->host = $request->input('host');
            $SnmpHostObj->type = $request->input('type','1');
            $SnmpHostObj->username = $request->input('username','');
            $SnmpHostObj->password = $request->input('password','');
            $SnmpHostObj->status = $request->input('status',SnmpHost::$_status['on']['code']);
            $SnmpHostObj->running = 0;
        }
        if($SnmpHostObj->save())
        {
            if($id) {
                event(new HostEvent([
                    "status"=>"info",
                    "host_id" => $SnmpHostObj->id,
                    "host" => $SnmpHostObj->host,
                    "type" => "HostChange",
                    "data" => [],
                    "time" => time(),
                ]));
            } else {
                $act = "add";
                event(new HostEvent([
                    "status"=>"info",
                    "host_id" => $SnmpHostObj->id,
                    "host" => $SnmpHostObj->host,
                    "type" => "HostAdd",
                    "data" => [],
                    "time" => time(),
                ]));
            }
            $resList = [
                'data' => [
                    'id' => $SnmpHostObj->id
                ]
            ];
            $id = $SnmpHostObj->id;
            $role = $request->input('role');
            $role = explode(',',$role);
            if($act != 'changeStatus') {
                if(count($role)>0)
                {
                    SnmpHostRole::where("hostId", $id)->where('type','!=','iptable')->whereNotIn('type',$role)->delete();
                }
                else
                {
                    SnmpHostRole::where("hostId", $id)->where('type','!=','iptable')->delete();
                }
                if($role) {
                    $roleDara = [];
                    foreach ($role as $value) {
                        if($value) {
                            if (SnmpHostRole::where("hostId", $id)->where('type', $value)->doesntExist()) {
                                $roleDara[] = ["hostId" => $id, "type" => $value, "running" => 0, "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")];
                            }
                        }
                    }
                    if ($act == "add" && !in_array("iptable", $role)) {
                        if (!SnmpHostRole::where("hostId", $id)->where('type', 'iptable')->doesntExist()) {
                            $roleDara[] = ["hostId" => $id, "type" => 'iptable', "running" => 0, "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")];
                        }
                    }
                    if (count($roleDara) > 0) {
                        SnmpHostRole::insert($roleDara);
                    }
                }
                if(SnmpHostRole::where("hostId", $id)->where('type','iptable')->doesntExist()) {
                    SnmpHostRole::insert(["hostId"=>$id, "type"=>'iptable', "running" => 0, "created_at"=>date("Y-m-d H:i:s"), "updated_at"=>date("Y-m-d H:i:s")]);
                }
            }
            $NotificationSettingId = $request->input('NotificationSettingId');
            if($NotificationSettingId) {
                $NotificationSettingId = explode(',', $NotificationSettingId);
                HostNotificationSetting::where("hostId", $id)->delete();
                $HostNotificationSettingDara = [];
                foreach ($NotificationSettingId as $value) {
                    $HostNotificationSettingDara[] = ["hostId" => $id, "notificationId" => $value, "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")];
                }
                if (count($HostNotificationSettingDara) > 0) {
                    HostNotificationSetting::insert($HostNotificationSettingDara);
                }
            }
            return ["status" => "success", "des" => '保存成功', "res" => $resList];
        }
        return ["status" => "fail", "des" => '保存失败，存在相同数据', 'res'=>[]];
    }

    /**
     * 服务器删除
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        Log::info("服务器信息删除", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $SnmpHostObj = SnmpHost::find($request->input('id'));
        if($SnmpHostObj->delete()){
            event(new HostEvent([
                "status"=>"info",
                "host_id" => $SnmpHostObj->id,
                "host" => $SnmpHostObj->host,
                "type" => "HostDel",
                "data" => [],
                "time" => time(),
            ]));
            SnmpHostRole::where("hostId", $request->input('id'))->delete();
            HostNotificationSetting::where("hostId", $request->input('id'))->delete();
            return ["status" => "success", "des" => '删除成功', 'res'=>[]];
        }
        return ["status" => "fail", "des" => '删除失败', 'res'=>[]];
    }

    /**
     * 启用的服务器列表
     * @param Request $request
     * @return array
     */
    public function EnableList(Request $request)
    {
        Log::info("获取启用的服务器列表信息", ["request" => $request]);

        $query = SnmpHost::query();

        $query->orderBy("updated_at", 'DESC');
        $query->where('status',1);

        $HostList = $query->with('role');


        if($HostList->exists())
        {
            $HostList = $HostList->get(["id", "name", "host", "running", "type", "created_at", "updated_at"])
            ->toArray();
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
                        $system_version = explode('.',str_replace(["Linux release ","(Core)"],"",$host["last_server_info"]["system_version"]));
                        $HostList[$key]["system_version"] = $system_version[0].(array_key_exists(1,$system_version)?(".".$system_version[1]):"");
                        $HostList[$key]["cpu_core_num"] = $host["last_server_info"]["cpu_core_num"];
                        $HostList[$key]["memory_total"] = getSize($host["last_server_info"]["memory_total"]);
                        $HostList[$key]["storageInfo"] = $this->storageInfoFormat($host["last_server_info"]["storage_info"]);
                        $HostList[$key]["storageWarning"] = $this->checkStorage(json_decode($host["last_server_info"]["storage_info"],true),$host["id"],6);
                        $HostList[$key]["time"] = strtotime($host["last_server_info"]["created_at"]);
                    }
                }
                if(!$last_server_info) {
                    $HostList[$key]["cpuUse"] = 0.00;
                    $HostList[$key]["cpuLoad1"] = 0.00;
                    $HostList[$key]["cpuLoad5"] = 0.00;
                    $HostList[$key]["cpuLoad15"] = 0.00;
                    $HostList[$key]["memUsedPercent"] = 0.00;
                    $HostList[$key]["netSpeed"] = ["in"=>["size"=>0,"format"=>"byte"],"out"=>["size"=>0,"format"=>"byte"]];
                    $HostList[$key]["system_version"] = "";
                    $HostList[$key]["cpu_core_num"] = "";
                    $HostList[$key]["memory_total"] = "";
                    $HostList[$key]["storageInfo"] = [];
                    $HostList[$key]["storageWarning"] = true;
                    $HostList[$key]["time"] = 0;
                }
                $role = [];
                foreach($host["role"] as $SnmpHostRole)
                {
                    $SnmpRole = SnmpRole::where("key",$SnmpHostRole["type"])->first();
                    if($SnmpRole) {
                        $SnmpRole = $SnmpRole->toArray();
                        $role[] = [
                            "roleId"=>$SnmpHostRole["id"],
                            "type"=>$SnmpHostRole["type"],
                            "running"=>$SnmpHostRole["running"],
                            "name"=>$SnmpRole["name"],
                        ];
                    }
                }
                $HostList[$key]["role"] = $role;
                unset($HostList[$key]["last_server_info"]);
            }
            $resList = array(
                'data' => $HostList
            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }

    /**
     * 启用的服务器详情
     * @param Request $request
     */
    public function EnableInfo(Request $request)
    {
        $hostId = $request->input('hostId');

        if($hostId) {
            $hostInfoObj = ServerInfo::query();
            $HostInfoList = $hostInfoObj->where('hostId', $hostId)
                ->orderBy("created_at", "DESC")
                ->with('snmpHost')
                ->first();
            if ($HostInfoList) {
                $HostInfoList = $HostInfoList->toArray();
                $HostInfoData = [];
                $HostInfoData["data"] = [
                    "name" => $HostInfoList["snmp_host"]["name"],
                    "ip" => $HostInfoList["snmp_host"]["host"],
                    "type" => $HostInfoList["snmp_host"]["type"],
                    "systemVersion" => $HostInfoList["system_version"],
                    "cpuCoreNum" => $HostInfoList["cpu_core_num"],
                    "cpuInfo" => $HostInfoList["cpu_info"],
                    "cpuUse" => $HostInfoList["cpu_use"],
                    "cpuLoad1" => $HostInfoList["cpu_load1"],
                    "cpuLoad5" => $HostInfoList["cpu_load5"],
                    "cpuLoad15" => $HostInfoList["cpu_load15"],
                    "memoryTotal" => getSize($HostInfoList["memory_total"]),
                    "memoryUse" => getSize($HostInfoList["memory_use"]),
                    "memUsedPercent" => $HostInfoList["mem_used_percent"],
                    "systemRuntime" => $HostInfoList["system_runtime"],
                    "systemTime" => $HostInfoList["system_time"],
                    "toDayLoginFail" => $HostInfoList["today_login_error_totalCount"],
                    "toDayLoginSucceed" => $HostInfoList["today_login_success_totalCount"],
                    "diskInfo" => $this->diskInfoFormat($HostInfoList["disk_info"]),
                    "storageInfo" => $this->storageInfoFormat($HostInfoList["storage_info"]),
                    "netSpeed" => $this->ifInfoFormat($HostInfoList["net_speed"]),
                    "diskIo" => $this->ioInfoFormat($HostInfoList["disk_io"]),
                    "tcpPort" => json_decode($HostInfoList["tcp_port"], true),
                    "udpPort" => json_decode($HostInfoList["udp_port"], true),
                    "runningServers" => json_decode($HostInfoList["hrswrunname"], true),
                    "running" => $HostInfoList["snmp_host"]["running"],
                    "updated_at" => $HostInfoList["updated_at"],
                ];
                return ["status" => "success", "des" => "获取成功", "res" => $HostInfoData];
            }
            return ["status" => "success", "des" => "无数据", "res" => []];
        }
        return ["status" => "fail", "des" => "请选择主机", "res" => []];
    }

    /**
     * 获取加密服务器的用户名和密码
     * @param Request $request
     * @return array
     */
    public function getEncryptData(Request $request)
    {
        $password = Rsa::deRSA_private($request->input("password"));
        if($password){
            $userInfo = $user = auth()->user();
            if(Hash::check($password,$userInfo->password))
            {
                try{
                    $hostUserName = Crypt::decryptString($request->input("hostUserName"));
                    $hostPassword = Crypt::decryptString($request->input("hostPassword"));
                    $data = ["username"=>$hostUserName,"hostPassword"=>$hostPassword];
                    return ["status" => "success", "des" => "获取成功", "res" => ["data"=>$data]];
                } catch (DecryptException $e) {
                    return ["status" => "success", "des" => "解密失败,数据不完整", "res" => ["message"=>$e->getMessage()]];
                }
            }
            return ["status" => "fail", "des" => "效验失败", "res" => []];
        }
        return ["status" => "fail", "des" => "请输入密码", "res" => []];
    }


    protected function checkStorage($storageInfo,$hostId,$type)
    {
        $flag = false;
        $HostNotificationSettingObj = HostNotificationSetting::join('notification_setting','notification_setting.id','=','host_notification_setting.notificationId')->where("host_notification_setting.hostId",$hostId)->where("notification_setting.type",$type)->where("notification_setting.status",1)->select('notification_setting.value');
        if($HostNotificationSettingObj->doesntExist()) {
            return $flag;
        }
        $HostNotificationSettingObj = $HostNotificationSettingObj->first();
        if(array($storageInfo)) {
            foreach($storageInfo as $row) {
                if(intval($row["used_percent"]) >= intval($HostNotificationSettingObj->value)) {
                    $flag = true;
                }
            }
        }
        return $flag;
    }
}
