<?php

namespace App\Console\Commands;

use App\Servers\NotificationRole\NotificationRole;
use App\Servers\snmp\SnmpInfo;
use Illuminate\Console\Command;

class NginxInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snmp:nginxInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取nginx信息';

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
        // $result = snmp2_walk("47.104.96.84", "public", config('oid.sysInfo.list.ifInfo.list.NetSpeed.oid'), config('snmp.timeout'));
        // $result = json_decode(json_decode(str_replace("STRING: ","", $result["9"]),true),true);
        // $result = SnmpInfo::ifInfo("47.104.96.84","1");
        // dd($result);
        NotificationRole::toEmail("2","1",[
            "subject"=>"主机离线告警通知",
            "message"=>"无法获取主机状态，当前主机已经离线，请尽快检查主机运行状态。",
            "dateTime"=>date("Y-m-d H:i:s")
        ]);
        // return 0;
    }
}
