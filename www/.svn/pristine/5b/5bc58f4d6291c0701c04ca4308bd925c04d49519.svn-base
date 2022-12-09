<?php

namespace Database\Seeders;

use App\Models\SnmpHostRole;
use App\Models\SnmpRole;
use Illuminate\Database\Seeder;

class SnmpRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name'=>"Apache服务","key"=>"httpd"],
            ['name'=>"Redis服务","key"=>"redis"],
            ['name'=>"Ipcc服务","key"=>"ipcc"],
            ['name'=>"数据库服务","key"=>"mysql"],
            ['name'=>"负载均衡服务","key"=>"nginx"],
            ['name'=>"Docker服务","key"=>"docker"],
            ['name'=>"防火墙服务","key"=>"iptable"],
        ];

        SnmpRole::where("key","apache")->delete();
        SnmpHostRole::where('type','apache')->update(["type"=>"httpd"]);

        foreach($data as $row)
        {
            if(SnmpRole::where("name",$row["name"])->where("key",$row["key"])->doesntExist())
            {
                SnmpRole::create($row);
            }
        }
    }
}
