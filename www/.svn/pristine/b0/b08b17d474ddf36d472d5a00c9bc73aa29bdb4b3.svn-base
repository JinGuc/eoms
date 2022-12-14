<?php
namespace App\Servers\snmp;
use App\Events\Snmp\SysWarningEvent;
use App\Models\HostNotificationSetting;
use App\Models\SnmpOid;

use App\Events\Snmp\SysInfoEvent;
use Illuminate\Support\Facades\Log;

class SnmpInfo
{
    protected static $host = '';
    protected static $name = '';

    public static function get($host = '', $name = "", $serviceName = "", $ctrl = "")
    {
        if ($host && $name) {
            if (method_exists(new SnmpInfo(), $name)) {
                if(in_array($name,['serviceStatus','serviceCtrl','serviceConnCount'])){
                    return self::$name($host,$serviceName,$ctrl);
                }else{
                    return self::$name($host);
                }
            } else {
                //return 'ERROR';
                $result['Data'] = [];
                return ['success' => false, 'result' => $result];
            }
        } else {
            //return 'ERROR';
            $result['Data'] = [];
            return ['success' => false, 'result' => $result];
        }
    }
    protected static function formatResult($result, $selfname = '')
    {
        $r = [];
        foreach ($result as $k => $v) {
            $str = str_replace(array('UCD-SNMP-MIB::', 'INTEGER: ', 'STRING: ', 'Counter32: ', 'Timeticks: ', 'kB', '"'), '', $v);
            $str = str_replace("'", '"', $str);
            $str = str_replace('.0,-7:0', '', $str);
            $str = str_replace('.0,+8:0', '', $str);
            $r[] = $str;
        }
        $re['Data'][$selfname] =  $r;
        if (in_array($selfname, ['tcpCountInfo', 'memInfo', 'diskOtherInfo', 'sIfInfo', 'diskReadInfo', 'openPortInfo','diskIoInfo','iptablesRules','serviceStatus','serviceCtrl','serviceConnCount','hrSWRunNamepy'])) {
            $m = count($r) - 1;
            $rs = $r[$m];
            $result = json_decode($rs, true);
            if (is_array($result)) {
                if (!empty($result['disks'] ?? '')) {
                    $disks_str = $result['disks'];
                    $disks_ary = explode('@', $disks_str);
                    $disks = [];
                    foreach ($disks_ary as $k => $v) {
                        $diskinfo = explode(':', $v);
                        if(count($diskinfo)>3) {
                            $disks[$k]['disk_name'] = $diskinfo[0];
                            if(empty($diskinfo[2])){
                                $diskinfo[2] = 0;
                            }
                            if(empty($diskinfo[1])){
                                $diskinfo[1] = 0;
                            }
                            $disks[$k]['disk_size'] = ceil($diskinfo[1] * 0.93) * 1024 * 1024;
                            $disks[$k]['disk_used_size'] = intval($disks[$k]['disk_size'] * $diskinfo[2] / 100);
                            $disks[$k]['disk_used_percent'] = $diskinfo[2]??0;
                            $disks[$k]['disk_indoes_used_percent'] = $diskinfo[3]??0;
                            if (!empty($diskinfo[4] ?? 0)) {
                                $disks[$k]['disk_read_speed'] = $diskinfo[4];
                            }
                        }
                    }
                    $result = $disks;
                }
                if (!empty($result['DiskIo'] ?? '')) {
                    $disksIo_str = $result['DiskIo'];
                    $disksIo_ary = explode('@', $disksIo_str);
                    $disksIo = [];
                    foreach ($disksIo_ary as $k => $v) {
                        $diskioinfo = explode(':', $v);
                        $disksIo[$k]['disk_name'] = $diskioinfo[0];
                        $disksIo[$k]['tps'] = sprintf('%.2f',$diskioinfo[1]);
                        $disksIo[$k]['kB_read_avg'] = sprintf('%.2f',$diskioinfo[2]);//KB/s
                        $disksIo[$k]['kB_wrtn_avg'] = sprintf('%.2f',$diskioinfo[3]);//kB/s
                        $disksIo[$k]['kB_read_total'] = $diskioinfo[4];//kB
                        $disksIo[$k]['kB_wrtn_total'] = $diskioinfo[5];//kB
                        $disksIo[$k]['await'] = sprintf('%.2f',$diskioinfo[6]);//百分比
                        $disksIo[$k]['util'] = sprintf('%.2f',$diskioinfo[7]);//百分比
                    }
                    $result = $disksIo;                   
                }
                if (!empty($result['process_list'] ?? '')) {
                    $process_list = $result['process_list'];
                    $result = $process_list;
                    $re['Data'][$selfname] =  $result;                
                }
                if (!empty($result['tcp_port'] ?? '')) {
                    $tcp_port_str = $result['tcp_port'];
                    $tcp_port_ary = explode(',', $tcp_port_str);
                    $tcp_ports = [];
                    foreach ($tcp_port_ary as $k => $v) {
                        $tcp_port_info = explode('|', $v);
                        $tcp_ports[$k]['recv_q'] = $tcp_port_info[0];
                        $tcp_ports[$k]['send_q'] = $tcp_port_info[1];
                        preg_match("/\d+\.\d+\.\d+\.\d+/",$tcp_port_info[2],$out);
                        $ip=$out[0]??'';
                        $port = str_replace([$ip,':::',':'],'',$tcp_port_info[2]);
                        $tcp_ports[$k]['port'] = $port;
                        $tcp_ports[$k]['name'] = $tcp_port_info[3];
                    }
                    $tcp_ports = array_column($tcp_ports, NULL, 'port');//以ID为索引
                    $tcp_ports = array_values($tcp_ports);//去除关联索引
                    $result['tcp_port'] = $tcp_ports;
                }
                if (!empty($result['udp_port'] ?? '')) {
                    $udp_port_str = $result['udp_port'];
                    $udp_port_ary = explode(',', $udp_port_str);
                    $udp_ports = [];
                    foreach ($udp_port_ary as $k => $v) {
                        $udp_port_info = explode('|', $v);
                        $udp_ports[$k]['recv_q'] = $udp_port_info[0];
                        $udp_ports[$k]['send_q'] = $udp_port_info[1];
                        preg_match("/\d+\.\d+\.\d+\.\d+/",$udp_port_info[2],$out);
                        $ip=$out[0]??'';
                        $port = str_replace([$ip,':::',':'],'',$udp_port_info[2]);
                        $udp_ports[$k]['port'] = $port;
                        $udp_ports[$k]['name'] = $udp_port_info[3];
                    }
                    $udp_ports = array_column($udp_ports, NULL, 'port');//以ID为索引
                    $udp_ports = array_values($udp_ports);//去除关联索引
                    $result['udp_port'] = $udp_ports;
                }
                $re['Data'][$selfname] =  $result;
            } else {
                $rs = str_replace(',', ' ', $rs);
                $re['Data'][$selfname] =  $rs;
            }
        } else {
            $r[0] = str_replace(',', ' ', $r[0]);
            $re['Data'][$selfname] =  $r;
        }
        return ['success' => true, 'result' => $re];
    }
    protected static function SysInfo($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }

    protected static function SysDesc($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.SysDesc.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function sysDescr($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.sysDescr.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function sysUptime($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.sysUptime.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }

    protected static function sysContact($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.sysContact.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }

    protected static function SysName($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.SysName.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }

    protected static function SysLocation($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.SysLocation.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }

    protected static function SysService($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.SysService.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }

    protected static function hrSWRunName($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.hrSWRunName.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function hrSWInstalledName($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.hrSWInstalledName.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }

    /**
     * 系统时间
     * @param string $host
     * @return array
     */
    protected static function hrSystemDate($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.hrSystemDate.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function hrSystemUptime($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.hrSystemUptime.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }

    /**
     * cpu
     * @param string $host
     * @return array
     */
    protected static function ssCpuIdle($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.ssCpuIdle.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function Load1($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.cpuLoad1.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function Load5($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.cpuLoad5.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function Load15($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.cpuLoad15.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function hrDeviceDescr($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.hrDeviceDescr.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }

    /**
     * 内存
     * @param string $host
     * @return array
     */
    protected static function memInfo($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.memInfo.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }

    /**
     * 硬盘
     * @param string $host
     * @return array
     */
    protected static function dskDevice($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.dskDevice.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function dskPath($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.dskPath.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function dskTotal($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.dskTotal.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function dskUsed($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.dskUsed.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function dskAvail($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.dskAvail.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function dskPercent($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.dskPercent.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function diskOtherInfo($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.diskOther.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function diskReadInfo($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.diskRead.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function serviceStatus($host = "127.0.0.1",$serviceName="")
    {
        if($serviceName=='httpd') $serviceName = 'apache';
        $oid = config('oid.services.list.'.$serviceName.'Status.oid')??'';
        if(empty($oid)){
            $oid = SnmpOid::where('serverName','=',$serviceName)->where('serverType','=','status')->value('oid');
        }
        $result = snmp2_walk($host, config('snmp.rocommunity'), $oid, config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function serviceCtrl($host = "127.0.0.1",$serviceName="",$ctrl="")
    {
        $oid = config('oid.services.list.'.$serviceName.$ctrl.'.oid');
        if(empty($oid)){
            $ctrl = strtolower($ctrl);
            $oid = SnmpOid::where('serverName','=',$serviceName)->where('serverType','=',$ctrl)->value('oid');
        }
        $result = snmp2_walk($host, config('snmp.rocommunity'), $oid, config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function serviceConnCount($host = "127.0.0.1",$serviceName="")
    {
        $oid = config('oid.services.list.'.$serviceName.'ConnCount.oid');
        if(empty($oid)){
            $oid = SnmpOid::where('serverName','=',$serviceName)->where('serverType','=','conncount')->value('oid');
        }
        $result = snmp2_walk($host, config('snmp.rocommunity'), $oid, config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    protected static function iptablesRules($host = "127.0.0.1",$serviceName="")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.iptablesRules.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    /**
     * 磁盘io
     * @param string $host
     * @return array
     */
    protected static function diskIoInfo($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.diskIo.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    /**
     * TCP连接数
     * @param string $host
     * @return array
     */
    protected static function tcpCountInfo($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.tcpCount.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }

    /**
     * 开放端口
     * @param string $host
     * @return array
     */
    protected static function openPortInfo($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.openPort.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }

    /**
     * 网络信息
     * @param string $host
     * @return array
     */
    protected static function sIfInfo($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.ifInfo.list.NetSpeed.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    /**
     * 进程信息
     * @param string $host
     * @return array
     */
    public static function hrSWRunNamepy($host = "127.0.0.1")
    {
        $result = snmp2_walk($host, config('snmp.rocommunity'), config('oid.sysInfo.list.hrSWRunNamepy.oid'), config('snmp.timeout'));
        return self::formatResult($result, __FUNCTION__);
    }
    /**
     * 系统信息
     * @param string $host
     * @param int $server_id
     * @return mixed
     */
    public static function hSysInfo($host = "127.0.0.1", $server_id = 0)
    {
        //$hrswrunname = self::get($host, 'hrSWRunName');
        $hrswrunname = self::get($host, 'hrSWRunNamepy');
        #print(type(hrswrunname))
        //$SysInfo['hrswrunname'] = json_encode(array_merge(array_unique($hrswrunname['result']['Data']['hrSWRunName'])),JSON_UNESCAPED_SLASHES);
        $SysInfo['hrswrunname'] = json_encode(($hrswrunname['result']['Data']['hrSWRunNamepy']??[]),JSON_UNESCAPED_SLASHES);
        #获取操作系统信息
        $sysinfo = SnmpInfo::get($host, 'sysDescr');
        $sysuptime = SnmpInfo::get($host, 'hrSystemUptime');
        $systime = SnmpInfo::get($host, 'hrSystemDate');
        $SysInfo['server_id'] = $server_id;
        $SysInfo['server_ip'] = $host;
        $SysInfo['system_version'] = $sysinfo['result']['Data']['sysDescr'][0];
        $SysInfo['system_runtime'] = $sysuptime['result']['Data']['hrSystemUptime'][0];
        $st = stripos($SysInfo['system_runtime'], '(');
        $et = stripos($SysInfo['system_runtime'], ')');
        $sysruntime_substr = substr($SysInfo['system_runtime'], $st, $et + 1);
        $SysInfo['system_runtime'] = str_replace(array($sysruntime_substr, 'days'), array('', 'days,'), $SysInfo['system_runtime']);
        $SysInfo['system_runtime'] = trim($SysInfo['system_runtime']);
        $SysInfo['system_time'] = $systime['result']['Data']['hrSystemDate'][0]??'';
        if(strpos($SysInfo['system_time'],' ')!==false){
            $SysInfo['system_time'] = str_replace('.0','',$SysInfo['system_time']);
            $stime = explode(' ',$SysInfo['system_time']);
            $stime[0] = $stime[0]??'';
            $stime[1] = $stime[1]??'';
            $SysInfo['system_time'] = $stime[0].' '.$stime[1];
        }
        if(empty($SysInfo['system_time'])){
            $SysInfo['system_time'] = '1900-01-01';
        }
        $meminfo = self::get($host, 'memInfo');
        $SysInfo['system_version'] = trim($meminfo['result']['Data']['memInfo']['osname']);
        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "type" => "BaseInfo",
            "data" => [
                "systemVersion"=>$SysInfo['system_version'],
                "systemRuntime"=>$SysInfo['system_runtime'],
                "systemTime"=>$SysInfo['system_time'],
                "hrswrunname"=>$hrswrunname['result']['Data']['hrSWRunNamepy']??[],
            ],
            "time" => time(),
        ]));
        return $SysInfo;
    }

    /**
     * CPU信息
     * @param string $host
     * @param int $server_id
     * @return mixed
     */
    public static function hCpuInfo($host = "127.0.0.1", $server_id = 0)
    {
        $ssCpuIdle = self::get($host, 'ssCpuIdle');
        $Load1 = self::get($host, 'Load1');
        $Load5 = self::get($host, 'Load5');
        $Load15 = self::get($host, 'Load15');
        $CpuInfo['cpu_use'] = 100 - $ssCpuIdle['result']['Data']['ssCpuIdle'][0];
        $CpuInfo['cpu_load1'] = $Load1['result']['Data']['Load1'][0];
        $CpuInfo['cpu_load5'] = $Load5['result']['Data']['Load5'][0];
        $CpuInfo['cpu_load15'] = $Load15['result']['Data']['Load15'][0];
        #获取设备信息
        $DeviceInfo = self::get($host, 'hrDeviceDescr');
        $cpu_info = $DeviceInfo['result']['Data']['hrDeviceDescr'];
        $cpu_count = 0;
        foreach ($cpu_info as $v) {
            if (strpos($v, 'GHz') !== false) {
                $cpu_count++;
            }
        }
        $CpuInfo['cpu_info'] = $cpu_info[0] ?? '';
        $CpuInfo['cpu_core_num'] = $cpu_count;
        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "type" => "CpuInfo",
            "data" => [
                "cpuUse"=>$CpuInfo['cpu_use'],
                "cpuLoad1"=>$CpuInfo['cpu_load1'],
                "cpuLoad5"=>$CpuInfo['cpu_load5'],
                "cpuLoad15"=>$CpuInfo['cpu_load15'],
                "cpuInfo"=>$CpuInfo['cpu_info'],
                "cpuCoreNum"=>$CpuInfo['cpu_core_num'],
            ],
            "time" => time(),
        ]));
        return $CpuInfo;
    }

    /**
     * 内存信息
     * @param string $host
     * @param int $server_id
     * @return mixed
     */
    public static function hMemInfo($host = "127.0.0.1", $server_id = 0)
    {
        $meminfo = self::get($host, 'memInfo');
        $MemInfo['memory_total'] = $meminfo['result']['Data']['memInfo']['memToal']??0;
        $MemInfo['memory_use'] = $meminfo['result']['Data']['memInfo']['memUsed']??0;
        $MemInfo['mem_used_percent'] = $meminfo['result']['Data']['memInfo']['memUsedPercent']??0;
        $MemInfo['today_login_error_totalCount'] = intval($meminfo['result']['Data']['memInfo']['today_login_error_totalCount']??0);
        $MemInfo['today_login_success_totalCount'] = intval($meminfo['result']['Data']['memInfo']['today_login_success_totalCount']??0);
        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "type" => "MemoryInfo",
            "data" => [
                "memoryTotal"=>getSize($MemInfo['memory_total']??0),
                "memoryUse"=>getSize($MemInfo['memory_use']??0),
                "memUsedPercent"=>$MemInfo['mem_used_percent'],
                "toDayLoginFail"=>$MemInfo['today_login_error_totalCount'],
                "toDayLoginSucceed"=>$MemInfo['today_login_success_totalCount'],
            ],
            "time" => time(),
        ]));
        return $MemInfo;
    }

    /**
     * 开放端口信息
     * @param string $host
     * @param int $server_id
     * @return mixed
     */
    public static function hOpenPortInfo($host = "127.0.0.1", $server_id = 0)
    {
        $openPortInfo = self::get($host, 'openPortInfo');
        $hOpenPortInfo['tcp_port'] = json_encode($openPortInfo['result']['Data']['openPortInfo']['tcp_port'], JSON_UNESCAPED_SLASHES);
        $hOpenPortInfo['udp_port'] = json_encode($openPortInfo['result']['Data']['openPortInfo']['udp_port'], JSON_UNESCAPED_SLASHES);
        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "type" => "OpenPortInfo",
            "data" => [
                "tcp"=>$openPortInfo['result']['Data']['openPortInfo']['tcp_port'],
                "udp"=>$openPortInfo['result']['Data']['openPortInfo']['udp_port'],
            ],
            "time" => time(),
        ]));
        return $hOpenPortInfo;
    }

    /**
     * TCP连接数信息
     * @param string $host
     * @param int $server_id
     * @return mixed
     */
    public static function hTcpCountInfo($host = "127.0.0.1", $server_id = 0)
    {
        $tcpCountInfo = self::get($host, 'tcpCountInfo');
        $hTcpCountInfo['tcp_connect_info'] = json_encode($tcpCountInfo['result']['Data']['tcpCountInfo']);
        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "type" => "TcpCountInfo",
            "data" => $tcpCountInfo['result']['Data']['tcpCountInfo'],
            "time" => time(),
        ]));
        return $hTcpCountInfo;
    }

    /**
     * 硬盘信息
     * @param string $host
     * @param int $server_id
     * @return mixed
     */
    public static function hStorageInfo($host = "127.0.0.1", $server_id = 0)
    {
        #硬盘挂载目录
        $res_dev = self::get($host, 'dskDevice');
        $res_dev = $res_dev['result']['Data']['dskDevice'];

        #硬盘挂载分区
        $res_path = self::get($host, 'dskPath');
        $res_path = $res_path['result']['Data']['dskPath'];
        #硬盘挂载目录总量
        $res_total = self::get($host, 'dskTotal');
        $res_total = $res_total['result']['Data']['dskTotal'];
        #硬盘挂载目录剩余量
        $res_dskAvail = self::get($host, 'dskAvail');
        $res_dskAvail = $res_dskAvail['result']['Data']['dskAvail'];
        #硬盘挂载目录使用量
        $res_dskUsed = self::get($host, 'dskUsed');
        $res_dskUsed = $res_dskUsed['result']['Data']['dskUsed'];
        #硬盘挂载目录使用百分比
        $res_used_Percent = self::get($host, 'dskPercent');
        $res_used_Percent = $res_used_Percent['result']['Data']['dskPercent'];
        $disks = [];
        foreach ($res_dev as $k1 => $v1) {
            if($res_path[$k1]!='/mnt/cdrom'&&strpos($res_path[$k1],'/cdrom')===false){
                $ds['partition'] = $v1;
                $ds['mounted'] = $res_path[$k1];
                $ds['total'] = $res_total[$k1];
                $ds['used'] = 0;
                $ds['used_percent'] = 0;
                $ds['used'] = bcsub($res_total[$k1] , $res_dskAvail[$k1]);
                if ($res_total[$k1] == 0 || $ds['used']==0) {
                    $ds['used_percent'] = 0;
                } else {
                    $ds['used_percent'] = $res_used_Percent[$k1]??0;
                }
                $disks[$k1] = $ds;
            }

        }
        $disks = a_array_unique($disks);
        $hStorageInfo['storage_info'] = json_encode($disks, JSON_UNESCAPED_SLASHES);
        $data = [];
        foreach ($disks as $v) {
            $tmp = $v;
            $tmp["total"] = getSize($v["total"]);
            $tmp["used"] = getSize($v["used"]);
            $data[] = $tmp;
        }

        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "type" => "StorageInfo",
            "data" => $data,
            "time" => time(),
        ]));
        event(new SysWarningEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "type" => "storage",
            "data" => ["warning"=>self::checkWarning($data,$server_id,6)],
            "time" => time(),
        ]));
        return $hStorageInfo;
    }

    /**
     * 硬盘概要信息
     * @param string $host
     * @param int $server_id
     * @return mixed
     */
    public static function hDiskInfo($host = "127.0.0.1", $server_id = 0)
    {
        $diskinfo = self::get($host, 'diskOtherInfo');
        $hDiskInfo['disk_info'] = json_encode($diskinfo['result']['Data']['diskOtherInfo'], JSON_UNESCAPED_SLASHES);
        $data = [];
        foreach ($diskinfo['result']['Data']['diskOtherInfo'] as $v) {
            $tmp = $v;
            $tmp["disk_size"] = getSize($v["disk_size"]);
            $tmp["disk_used_size"] = getSize($v["disk_used_size"]);
            $data[] = $tmp;
        }
        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "type" => "DiskInfo",
            "data" => $data,
            "time" => time(),
        ]));
        return $hDiskInfo;
    }

    /**
     * 网络接口概要
     * @param string $host
     * @param int $server_id
     * @return mixed
     */
    public static function ifInfo($host = "127.0.0.1", $server_id = 0){
        $ifInfo = self::get($host, 'sIfInfo');

        $sIfInfo = $ifInfo["result"]["Data"]["sIfInfo"];

        $data = [
            "original"=> $sIfInfo,
            "escape"=>[
                "in"=> getSize($sIfInfo["in"],true),
                "out"=>getSize($sIfInfo["out"],true),
            ]
        ];
        $ifInfo["if_info"] = json_encode($sIfInfo, JSON_UNESCAPED_SLASHES);
        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "type" => "ifInfo",
            "data" => $data,
            "time" => time(),
        ]));
        return $ifInfo;
    }

    /**
     * 硬盘IO信息
     * @param string $host
     * @param int $server_id
     * @return mixed
     */
    public static function hDiskIoInfo($host = "127.0.0.1", $server_id = 0)
    {
        $diskioinfo = self::get($host, 'diskIoInfo');
        $hDiskIoInfo['diskio_info'] = json_encode($diskioinfo['result']['Data']['diskIoInfo'], JSON_UNESCAPED_SLASHES);
        $data = [];
        foreach ($diskioinfo['result']['Data']['diskIoInfo'] as $v) {
            $tmp = $v;
            $tmp["kB_read_avg"] = getSize($v["kB_read_avg"]??0);
            $tmp["kB_wrtn_avg"] = getSize($v["kB_wrtn_avg"]??0);
            $tmp["util"] = $v["util"]??0;
            $data[] = $tmp;
        }
        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "type" => "DiskIoInfo",
            "data" => $data,
            "time" => time(),
        ]));
        return $hDiskIoInfo;
    }
    /**
     * 应用服务状态
     * @param string $host
     * @param int $server_id
     * @param string $serviceName 服务名称
     * @return mixed
     */
    public static function hServiceStatus($host = "127.0.0.1", $server_id = 0 ,$serviceName="")
    {
        if(empty($serviceName??'')){
            return [];
        }
        $info = self::get($host, 'serviceStatus',$serviceName);
        $data = $info['result']['Data']['serviceStatus']??[];
        $data['serviceName'] = $serviceName??'';
        $status_ = $data['status'] ?? '';
        if(!empty($status_)&&strpos($status_,'(')!==false){
            $status_ary = explode(";",$status_);
            $status__ = self::get_between($status_ary[0],'(',')');
            $data['status'] = $status__;
            if(strpos($data['status'],'run')===false){
                $data['status'] = 'stop';
            }
            $patten = "/(0?\d{1,4})[^\w\d\r\n:](0?[1-9]|1[0-2])[^\w\d\r\n:](\d{4}|\d{2})\s([0-9]{1,2}):([0-9]{2}):([0-9]{2})/i";
            preg_match_all($patten,$status_ary[0],$matches);
            $data['service_start_time'] = $matches[0][0]??'';
        }else{
            $data['status'] = $status_;  
            $data['service_start_time'] = '1900-01-01';
        }
        if(!empty($data['starttime']??'')){
            $data['service_start_time'] = $data['starttime'];
        }
        $data['request_time'] = date('Y-m-d H:i:s');
        $data['memUsedPercent'] = $data['memUsedPercent']??0;
        $hServiceStatus['info'] = json_encode($data, JSON_UNESCAPED_SLASHES);
        /*
        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "serviceName" => $serviceName,
            "type" => "ServiceStatus",
            "data" => $data,
            "time" => time(),
        ]));
        */
        return $hServiceStatus;
    }
    /**
     * 应用服务操作
     * @param string $host
     * @param int $server_id
     * @param string $serviceName 服务名称
     * @param string $ctrl 操作名称
     * @return mixed
     */
    public static function hServiceCtrl($host = "127.0.0.1", $server_id = 0 ,$serviceName="", $ctrl="")
    {
        if(empty($serviceName)||empty($ctrl)){
            return [];
        }
        $info = self::get($host, 'serviceCtrl',$serviceName,$ctrl);
        $data = $info['result']['Data']['serviceCtrl'];
        $data['serviceName'] = $serviceName;
        $status_ = $data['status'] ?? '';
        if(!empty($status_)){
            $status_ary = explode(";",$status_);
            $status__ = self::get_between($status_ary[0],'(',')');
            $data['status'] = $status__;
            $patten = "/(0?\d{1,4})[^\w\d\r\n:](0?[1-9]|1[0-2])[^\w\d\r\n:](\d{4}|\d{2})\s([0-9]{1,2}):([0-9]{2}):([0-9]{2})/i";
            preg_match_all($patten,$status_ary[0],$matches);
            $data['service_start_time'] = $matches[0][0];
        }
        $data['request_time'] = date('Y-m-d H:i:s');
        $hServiceCtrl['info'] = json_encode($data, JSON_UNESCAPED_SLASHES);
        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "serviceName" => $serviceName,
            "type" => "ServiceCtrl",
            "data" => $data,
            "time" => time(),
        ]));
        return $hServiceCtrl;
    }
    /**
     * 应用服务连接数
     * @param string $host
     * @param int $server_id
     * @param string $serviceName 服务名称
     * @return mixed
     */
    public static function hServiceConnCount($host = "127.0.0.1", $server_id = 0 ,$serviceName="")
    {
        if(empty($serviceName)||$serviceName=='iptable'){
            return [];
        }
        $info = self::get($host, 'serviceConnCount',$serviceName);
        $data = $info['result']['Data']['serviceConnCount'];
        $data = json_encode($data, JSON_UNESCAPED_SLASHES);
        $hServiceConnCount['info'] = $data;
        // $data['serviceName'] = $serviceName;
        /*
        event(new SysInfoEvent([
            "status"=>"info",
            "host_id" => $server_id,
            "host" => $host,
            "serviceName" => $serviceName,
            "type" => "ServiceConnCount",
            "data" => $data,
            "time" => time(),
        ]));
        */
        return $hServiceConnCount;
    }
    /**
     * 启用的防火墙规则
     * @param string $host
     * @param int $server_id
     * @param string $serviceName 服务名称
     * @return mixed
     */
    public static function hIptablesRules($host = "127.0.0.1", $server_id = 0)
    {
        $info =  self::get($host, 'iptablesRules');
        $hIptablesRules = json_encode($info, JSON_UNESCAPED_SLASHES);
        return $hIptablesRules;
    }
    public static function get_between($input, $start, $end)
    {
        $substr = substr($input, strlen($start) + strpos($input, $start), (strlen($input) - strpos($input, $end)) * (-1));
        return $substr;
    }

    protected static function checkWarning($storageInfo,$hostId,$type)
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