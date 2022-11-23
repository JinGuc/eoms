<?php

namespace App\Console\Commands;

use App\Models\NotificationSetting;
use App\Servers\NotifiCation;
use Illuminate\Console\Command;

class HostWarning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notice:HostWarning';

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
        $time = date('Y-m-d',strtotime('-30 days'));
        $time2 = date('Y-m-d H:i:s',time());
        $res = NotificationSetting::where('status','=',1)->get();
        if(!empty($res)){
            $res=$res->toArray();
            foreach($res as $v){
                $noticeSettingId = $v['id'];
                $type = $v['type'];
                $countType = $v['countType'];
                $cycle = $v['cycle'];
                $operator = $v['operator'];
                $value = $v['value'];
                $ContactId  = $v['ContactId'];
                $content = $v['content'];
                $sendType = $v['sendType'];
                $continueCycle = $v['continueCycle'];
                $silenceCycle = $v['silenceCycle'];
                $title = $v['title'];
                $ContactIds = explode(',',$ContactId);
                $time1 = date('Y-m-d H:i:s',time()-$cycle*60);
                $params['title'] = $title;
                $params['type'] = $type;
                $params['time1'] = $time1;
                $params['time2']  = $time2;
                $params['countType'] = $countType;
                $params['operator'] = $operator;
                $params['value'] = $value;
                $params['sendType'] = $sendType;
                $params['content'] = $content;
                $params['ContactId'] = $ContactId;
                $params['continueCycle'] = $continueCycle;
                $params['silenceCycle'] = $silenceCycle;
                $params['noticeSettingId'] = $noticeSettingId;
                NotifiCation::getwarningValue($params);
            }
        }
    }
}
