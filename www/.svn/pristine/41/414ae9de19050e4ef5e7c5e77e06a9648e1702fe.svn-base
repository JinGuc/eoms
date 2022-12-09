<?php

namespace App\Console\Commands;

use App\Events\Snmp\HostEvent;
use App\Models\SnmpHost;
use App\Models\WebSetting;
use App\Servers\Snmp;
use App\Servers\snmp\snmpCheck;
use Illuminate\Console\Command;

class HostInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snmp:hostInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'snmp 获取主机的状态';

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
        $hostHeart = WebSetting::where("fd_name","host_heartbeat")->first();
        if(!$hostHeart) {
            $hostHeart = 5;
        }
        else {
            $hostHeart = $hostHeart->value;
        }
        for($time=0; $time<12; $time++) {
            $list = Snmp::getHost();
            foreach ($list as $host) {
                $result = snmpCheck::check($host["host"]);
                if (!$result) {
                    if ($host["heartNum"] > $hostHeart) {
                        if($host["running"]==1) {
                            event(new HostEvent([
                                "status" => "info",
                                "host_id" => $host["id"],
                                "host" => $host["host"],
                                "type" => "status",
                                "data" => ["running" => 0],
                                "time" => time(),
                            ]));
                        }
                        SnmpHost::find($host["id"])->update(["running" => "0", "heartNum" => 0]);
                    } else {
                        SnmpHost::find($host["id"])->update(["heartNum" => $host["heartNum"] + 1]);
                    }
                } else {
                    if($host["running"]==0) {
                        event(new HostEvent([
                            "status" => "info",
                            "host_id" => $host["id"],
                            "host" => $host["host"],
                            "type" => "status",
                            "data" => ["running" => 1],
                            "time" => time(),
                        ]));
                    }
                    SnmpHost::find($host["id"])->update(["running" => "1", "heartNum" => 0]);
                }
            }
            sleep(5);
        }
        return 0;
    }
}
