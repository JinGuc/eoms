<?php
namespace App\Servers;

use App\Models\SnmpHost;
use App\Models\SnmpHostRole;
use App\Models\ServerInfo;
use App\Models\SnmpHostInfo;
use App\Models\UrlStatusInfo;

class Snmp
{
    /*
    *获取主机服务器列表
    */
    public static function getHost(){
        $res = SnmpHost::where('status',1)->get();
        if(!empty($res)){
            return $res->toArray();
        }else{
            return [];
        }
    }
    /*
    *获取主机服务器信息
    */
    public static function getHostInfo($HostId){
        $res = SnmpHost::where('id',$HostId)->get();
        if(!empty($res)){
           $re  = $res->toArray();
           return $re[0];
        }else{
            return [];
        }
    }
    
    /*
    *获取主机服务信息
    */
    public static function getHostRole($hostId=0){
        $res = SnmpHostRole::where('hostId',$hostId)->get();
        if(!empty($res)){
            return $res->toArray();
        }else{
            return [];
        }
    }
    /*
    *添加主机采集信息
    */
    public static function insertServerInfo($params=[]){
        $res = ServerInfo::create($params);
        return $res;
    }
    /*
    *添加主机服务采集信息
    */
    public static function insertHostInfo($params=[]){
        $res = SnmpHostInfo::create($params);
        return $res;
    }
    /*
    *添加URL接口状态信息
    */
    public static function insertUrlStatusInfo($params=[]){
        $res = UrlStatusInfo::create($params);
        return $res;
    }
}