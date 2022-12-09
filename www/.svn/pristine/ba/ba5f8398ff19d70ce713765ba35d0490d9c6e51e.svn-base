<?php

namespace App\Console\Commands;

use App\Models\NotificationInfo;
use App\Models\NotificationLog;
use Illuminate\Console\Command;

class WarningLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notice:WarningLog';

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
                $noticeId =  $v['id'];
                $silenceCycle = $v['silenceCycle'];
                $created_at = NotificationLog::where('notificationId',$noticeId)->orderBy('id', 'desc')->limit(1)->value('created_at');
                if(time()-strtotime($created_at)>$silenceCycle*60&&time()<=strtotime($v['stopNoticeTime'])){
                    $data = [
                        'hostId' => $v['hostId'],
                        'notificationSettingId' => $v['notificationSettingId'],
                        'notificationId' => $noticeId,
                        'notificationType' => $v['notificationType'],
                        'sendType' => $v['sendType'],
                        'ContactId' => $v['ContactId'],
                        'info' => $v['content'],
                        'created_at' => date('Y-m-d H:i:s'),                    
                    ];
                    NotificationLog::insertGetId($data);
                }
            }
        }
    }
}
