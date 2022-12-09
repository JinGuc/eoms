<?php

namespace App\Console\Commands;

use App\Servers\Snmp;
use App\Servers\snmp\SnmpInfo;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SysInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snmp:sysInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取系统信息';

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
        $list = Snmp::getHost();
        //for($time=0; $time<1; $time++) {
            foreach ($list as $k => $v) {
                try {
                    $server_id = $v['id'];
                    $device_ip = $v['host'];
                    $params['hostId'] = $server_id;
                    $hSysInfo = SnmpInfo::hSysInfo($device_ip, $server_id);
                    #print(type(hrswrunname))
                    $params['hrswrunname'] = $hSysInfo['hrswrunname'];
                    #获取操作系统信息
                    $params['system_version'] = $hSysInfo['system_version'];
                    $params['system_runtime'] = $hSysInfo['system_runtime'];
                    $params['system_time'] = $hSysInfo['system_time'];
                    #获取cpu使用率
                    $hCpuInfo = SnmpInfo::hCpuInfo($device_ip, $server_id);
                    $params['cpu_use'] = $hCpuInfo['cpu_use'];
                    $params['cpu_load1'] = $hCpuInfo['cpu_load1'];
                    $params['cpu_load5'] = $hCpuInfo['cpu_load5'];
                    $params['cpu_load15'] = $hCpuInfo['cpu_load15'];
                    $params['cpu_info'] = $hCpuInfo['cpu_info'];
                    $params['cpu_core_num'] = $hCpuInfo['cpu_core_num'];
                    #print(data)
                    #获取内存信息
                    $hMemInfo = SnmpInfo::hMemInfo($device_ip, $server_id);
                    $params['memory_total'] = $hMemInfo['memory_total'];
                    $params['memory_use'] = $hMemInfo['memory_use'];
                    $params['mem_used_percent'] = $hMemInfo['mem_used_percent'];
                    $params['today_login_error_totalCount'] = $hMemInfo['today_login_error_totalCount'];
                    $params['today_login_success_totalCount'] = $hMemInfo['today_login_success_totalCount'];
                    $hOpenPortInfo = SnmpInfo::hOpenPortInfo($device_ip, $server_id);
                    $params['tcp_port'] = $hOpenPortInfo['tcp_port'];
                    $params['udp_port'] = $hOpenPortInfo['udp_port'];
                    #print(res)
                    #获取TCP连接数
                    $hTcpCountInfo = SnmpInfo::hTcpCountInfo($device_ip, $server_id);
                    $params['tcp_connect_info'] = $hTcpCountInfo['tcp_connect_info'];
                    #获取硬盘信息

                    #diskinfo = np.array(diskinfo);
                    $hDiskInfo = SnmpInfo::hDiskInfo($device_ip, $server_id);
                    $params['disk_info'] = $hDiskInfo['disk_info'];;
                    $hStorageInfo = SnmpInfo::hStorageInfo($device_ip, $server_id);
                    $params['storage_info'] = $hStorageInfo['storage_info'];
                    $hDiskIoInfo = SnmpInfo::hDiskIoInfo($device_ip, $server_id);
                    $params['disk_io'] = array_key_exists("diskio_info", $hDiskIoInfo) ? $hDiskIoInfo['diskio_info'] : "{}";

                    #网络速度
                    $ifInfo = SnmpInfo::ifInfo($device_ip, $server_id);
                    $params["net_speed"] = array_key_exists("if_info", $ifInfo) ? $ifInfo['if_info'] : "{}";
                    Snmp::insertServerInfo($params);
                } catch (Exception $e) {
                    Log::error("获取主机信息失败",["id"=>$v["id"],"name"=>$v["name"],"ip"=>$v["host"],"message"=>$e->getMessage().' at File '.$e->getFile().' in Line '.$e->getLine()]);
                }
            }
            //sleep(60);
        //}
        //return 0;
    }
}
