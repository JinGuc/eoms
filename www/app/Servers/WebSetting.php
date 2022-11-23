<?php


namespace App\Servers;
use App\Models\WebSetting as WebS;


class WebSetting
{
    public static function all(){
        $res = WebS::where('status',1)->get(["fd_name","value"]);
        if(!empty($res)){
            $data = [];
            foreach($res->toArray() as $v)
            {
                $data[$v["fd_name"]] = $v["value"];
            }
            return $data;
        }else{
            return [];
        }
    }
}