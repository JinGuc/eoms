<?php

namespace Database\Seeders;
use App\Models\WebSetting;

use Illuminate\Database\Seeder;

class WebSettingSeeder20221114 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WebSetting::insert([
            ["fd_name"=>"serverinfo_savedays","status"=>1,"type"=>1,"value"=>"30","note"=>"主机服务采集信息保存天数（默认30天）"],
        ]);
        $date = date("Y-m-d H:i:s");
        WebSetting::whereNull("created_at")->update(["created_at"=>$date,"updated_at"=>$date]);
    }
}
