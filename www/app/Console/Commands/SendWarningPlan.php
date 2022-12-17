<?php

namespace App\Console\Commands;

use App\Models\NotificationSendinfo;
use App\Models\NotificationLog;
use App\Models\NotificationInfo;
use App\Models\NotificationSetting;
use App\Servers\NotifiCation;
use Illuminate\Console\Command;
#use NotificationInfo;

class SendWarningPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notice:SendWarningPlan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送告警信息';

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
        //exit();
        $time = date('Y-m-d H:i:s');
        $list = NotificationLog::where('status', 1)->orderBy('id', 'asc')->get();
        if(!empty($list)){
            $list = $list->toArray();
            foreach($list as $v){
                $notificationSettingId = $v['notificationSettingId'];
                $notificationId = $v['notificationId'];
                $args['notificationSettingId'] = $notificationSettingId??0;
                $res = NotifiCation::updateNoticeStatus($args);
                if(!$res){
                    continue 1;
                }else{
                    $v['sendType'] = $res['sendType'];
                    $v['ContactId'] = $res['ContactId'];
                    $v['sound_index'] = $res['sound_index'];
                }
                $noticeLogId = $v['id'];
                $sendType = $v['sendType'];
                $ContactId = $v['ContactId'];
                $hostId = $v['hostId'];
                $info = $v['info'];
                $sound_index = $v['sound_index'];
                $params=[
                'ContactId'=>$ContactId,
                'hostId'=>$hostId,
                'noticeLogId'=>$noticeLogId,
                'notificationId'=>$notificationId,
                'notificationSettingId'=>$notificationSettingId ?? 0,
                'info'=>$info,
                'sound_index' => $sound_index,
                ];
                switch($sendType){
                    case 1:
                        //电话
                        $params['type']=1;
                        NotifiCation::insertSendInfo($params);
                    break;
                    case 2:
                        //短信
                        $params['type']=2;
                        NotifiCation::insertSendInfo($params);
                    break;
                    case 3:
                        //邮件
                        $params['type']=3;
                        NotifiCation::insertSendInfo($params);
                    break;
                    case 4:
                        //电话
                        $params['type']=1;
                        NotifiCation::insertSendInfo($params);
                        //短信
                        $params['type']=2;
                        NotifiCation::insertSendInfo($params);
                    break;
                    case 5:
                        //电话
                        $params['type']=1;
                        NotifiCation::insertSendInfo($params);
                        //邮件
                        $params['type']=3;
                        NotifiCation::insertSendInfo($params);
                    break;
                    case 6:
                        //短信
                        $params['type']=2;
                        NotifiCation::insertSendInfo($params);
                        //邮件
                        $params['type']=3;
                        NotifiCation::insertSendInfo($params);
                    break;
                    case 7:
                        //电话
                        $params['type']=1;
                        NotifiCation::insertSendInfo($params);
                        //短信
                        $params['type']=2;
                        NotifiCation::insertSendInfo($params);
                        //邮件
                        $params['type']=3;
                        NotifiCation::insertSendInfo($params);
                    break;
                }
            }
        }
        //发送消息
        NotifiCation::SendWarningMsg($params=[]);
    }
}
