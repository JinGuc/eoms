<?php

namespace App\Console\Commands;

use App\Models\ServerInfo;
use App\Models\SnmpHostInfo;
use App\Models\WebSetting;
use Illuminate\Console\Command;

class DeleteServerInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snmp:deleteServerInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'snmp 删除历史服务器信息记录';

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
        $days = WebSetting::where('fd_name','=','serverinfo_savedays')->value('value')??30;
        $days = intval($days);
        if($days<=0) $days=30;
        $times = date('Y-m-d',strtotime('-'.$days.' days'));
        $res = ServerInfo::where('created_at','<',$times)->delete();
        $res = SnmpHostInfo::where('created_at','<',$times)->delete();
        return true;
    }
}
