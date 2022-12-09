<?php

namespace Database\Seeders;

use App\Models\WebSetting;
use Illuminate\Database\Seeder;

class WebSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WebSetting::insert([
            ["fd_name"=>"web_title","status"=>1,"type"=>1,"value"=>"金鼓运维管理系统","note"=>"系统名称"],
            ["fd_name"=>"verification_code","status"=>1,"type"=>1,"value"=>"1","note"=>"是否启用验证码"],
            ["fd_name"=>"green_verification_code","status"=>1,"type"=>2,"value"=>"jgc","note"=>"绿色验证码"],
            ["fd_name"=>"sms_type","status"=>1,"value"=>"1","type"=>2,"note"=>"短信平台类型 1 自己的"],
            ["fd_name"=>"sms_url","status"=>1,"type"=>2,"value"=>"http://127.0.0.1:8083/ms/get_ms_advertise.php","note"=>"短信平台接口"],
            ["fd_name"=>"call_type","status"=>1,"type"=>2,"value"=>"1","note"=>"呼叫中心类型 1 自己的"],
            ["fd_name"=>"call_url","status"=>1,"type"=>2,"value"=>"http://127.0.0.1:8000/call_person.php","note"=>"呼叫中心呼叫接口"],
            ["fd_name"=>"mail_matler","status"=>1,"type"=>2,"value"=>"smtp","note"=>"邮件协议"],
            ["fd_name"=>"mail_host","status"=>1,"type"=>2,"value"=>"","note"=>"邮件服务地址"],
            ["fd_name"=>"mail_port","status"=>1,"type"=>2,"value"=>"","note"=>"邮件服务端口"],
            ["fd_name"=>"mail_username","status"=>1,"type"=>2,"value"=>"","note"=>"邮件服务用户名"],
            ["fd_name"=>"mail_password","status"=>1,"type"=>2,"value"=>"","note"=>"邮件服务密码"],
            ["fd_name"=>"mail_encryption","status"=>1,"type"=>2,"value"=>"","note"=>"邮件服务加密方式"],
            ["fd_name"=>"mail_from_adders","status"=>1,"type"=>2,"value"=>"","note"=>"邮件显示发送邮箱"],
            ["fd_name"=>"mail_from_name","status"=>1,"type"=>2,"value"=>"","note"=>"邮件显示发送人"],
            ["fd_name"=>"host_heartbeat","status"=>1,"type"=>2,"value"=>"5","note"=>"主机心跳检测次数 既多少次内获取不到主机状态视为离线"],
            ["fd_name"=>"copy_right","status"=>1,"type"=>1,"value"=>"@2022 jinguc 版权所属","note"=>"版权信息"],
            ["fd_name"=>"filing_information","status"=>1,"type"=>1,"value"=>"","note"=>"备案信息"],
            ["fd_name"=>"server_heartbeat","status"=>1,"type"=>1,"value"=>"","note"=>"服务心跳检测次数 既多少次内获取不到服务状态为离线"],
            ["fd_name"=>"serverinfo_savedays","status"=>1,"type"=>1,"value"=>"30","note"=>"主机服务采集信息保存天数（默认30天）"],
        ]);
        $date = date("Y-m-d H:i:s");
        WebSetting::whereNull("created_at")->update(["created_at"=>$date,"updated_at"=>$date]);
    }
}
