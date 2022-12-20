<?php


namespace App\Servers\NotificationRole;


use App\Mail\NotificationMail;
use App\Models\Contact;
use App\Models\WebSetting;
use App\Models\SnmpHost;
use App\Models\UrlInfo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class NotificationRole
{
    public static function toSms($params)
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
        //$response = Http::get($url);
        //return $response->ok();
        //return true;
    }

    public static function toEmail($ContactId,$hostId,$content)
    {
        $ContactObj = Contact::find($ContactId);
        $type = $content["type"] ?? '';
        if($type=='api'){
            $urlObj = UrlInfo::find($hostId);
        }else{
            $hostObj = SnmpHost::find($hostId);
        }
        if($ContactObj->email) {
            if ($type == 'api') {
                $data = [
                    "subject" => $content["subject"],
                    "toUser" => $ContactObj->email ?? '',
                    "toUserName" => $ContactObj->name ?? '',
                    "hostName" => $urlObj->title ?? '',
                    "hostIP" => $urlObj->url ?? '',
                    "message" => $content["message"] ?? '',
                    "dateTime" => $content["dateTime"] ?? date('Y-m-d H:i:s'),
                    "type" => $content["type"] ?? '',
                ];
            } else {
                $data = [
                    "subject" => $content["subject"],
                    "toUser" => $ContactObj->email ?? '',
                    "toUserName" => $ContactObj->name ?? '',
                    "hostName" => $hostObj->name ?? '',
                    "hostIP" => $hostObj->host ?? '',
                    "message" => $content["message"] ?? '',
                    "dateTime" => $content["dateTime"] ?? date('Y-m-d H:i:s'),
                    "type" => $content["type"] ?? '',
                ];
            }
            Mail::send(new NotificationMail($data));
        }
    }

    public static function toTel($params)
    {
        $callUrl=WebSetting::where('fd_name','=','call_url')->value('value');
        $show_no=WebSetting::where("fd_name",'=',"task_show_no")->value('value');
        if(!empty($params['sound_index'])){
            $url=$callUrl.'?act=call&phones='.$params['mobile'].';;'.$params['sound_index'].';;1&task_type=2&show_no='.$show_no.'&bili_type=2';
            $response = Http::get($url);
            //return $response->ok();
            return true;
        }else{
            return false;
        }
    }
}