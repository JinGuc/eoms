<?php
namespace App\Servers;

use App\Models\WebSetting;


class SendMsg{
    //告警方式 1电话 2短信 3邮件 4电话+短信 5电话+邮件 6短信+邮件 7电话+短信+邮件
    
    public static function sendPhone($params)
    {
        //电话提醒

    }
    public static function sendSms($params)
    {
        //短信提醒
        $sendUrl=WebSetting::where('fd_name','=','sms_url')->value('value');
        $User='10000';
        $Passwd=md5('123456');
        $Phone=$params['mobile'];
        $Content=$params['content'];
        $url = $sendUrl.'?name='.$User.'&pwd='.$Passwd.'&phone='.$Phone.'&msg='.urlencode($Content);
        //file_put_contents(storage_path('logs/jingu-eoms-semdsms.log'),$url."\r\n",FILE_APPEND);
        return request_by_curl($url);

    }
    public static function sendEmail($params)
    {
        //邮件提醒

    }
}
