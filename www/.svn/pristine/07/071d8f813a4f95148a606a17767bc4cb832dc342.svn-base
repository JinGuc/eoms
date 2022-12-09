<?php

namespace App\Console\Commands;

use App\Models\NotificationInfo;
use App\Models\NotificationSetting;
use App\Models\NotificationSendinfo;
use App\Models\NotificationLog;
use App\Servers\NotifiCation;
use Illuminate\Console\Command;

class InsertWarningLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notice:InsertWarningLog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '告警任务';

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
        $time = date('Y-m-d H:i:s');
        $list = NotificationInfo::where('stopNoticeTime', '>=', $time)->where('status', '=', 0)->orderBy('id', 'asc')->get();
        if(!empty($list)){
            $list = $list->toArray();
            foreach($list as $v){
                $notificationId =  $v['id'];
                $silenceCycle = $v['silenceCycle'];
                $notificationSettingId = $v['notificationSettingId'];
                $relate_table = $v['relate_table']??'';
                $relate_id = $v['relate_id']??0;
                $nstatus = 1;
                $args['notificationSettingId'] = $notificationSettingId ?? 0;
                $args['relate_table'] = $relate_table;
                $args['relate_id'] = $relate_id;
                if (!NotifiCation::updateNoticeStatus($args)) {
                    $nstatus = 0;
                    continue 1;
                }
                $created_at = NotificationLog::where('notificationId','=',$notificationId)->orderBy('id', 'desc')->limit(1)->value('created_at');
                if(time()-strtotime($created_at)>$silenceCycle*60&&time()<=strtotime($v['stopNoticeTime'])&&$nstatus==1){
                    $data = [
                        'hostId' => $v['hostId'],
                        'notificationSettingId' => $v['notificationSettingId'],
                        'notificationId' => $notificationId,
                        'notificationType' => $v['notificationType'],
                        'sendType' => $v['sendType'],
                        'ContactId' => $v['ContactId'],
                        'sound_index' => $v['sound_index'],
                        'info' => $v['data'],
                        'created_at' => date('Y-m-d H:i:s'),                    
                    ];
                    NotificationLog::insertGetId($data);
                }
            }
        }
    }
}
