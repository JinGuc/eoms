<?php


namespace App\Servers\snmp;



use App\Events\Snmp\SysInfoEvent;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\Channel;
use BeyondCode\LaravelWebSockets\WebSockets\Messages\PusherMessageFactory;

class SysInfo
{
    protected static $host=[];

    public static function get($name="")
    {
        if($name)
        {
            if(method_exists(new SysInfo(),$name))
            {
                return self::$name();
            }
        }
        return self::SysInfo();
    }

    protected static function SysInfo($host="")
    {
        $result = snmp2_walk("127.0.0.1","public",config('oid.sysInfo.oid'));
        event(new SysInfoEvent([
            "status"=>"info",
            "hostId"=>"127.0.0.1",
            "type"=>"SysInfo",
            "data"=>$result,
            "time"=>time(),
        ]));
        return $result;
    }

    protected static function SysDesc($host="")
    {
        $result = snmp2_walk("127.0.0.1","public",config('oid.sysInfo.list.SysDesc.oid'));
        event(new SysInfoEvent([
            "status"=>"info",
            "host"=>"127.0.0.1",
            "type"=>"SysDesc",
            "data"=>$result,
            "time"=>time(),
        ]));
        return $result;
    }

    protected static function sysUptime($host="")
    {
        $result = snmp2_walk("127.0.0.1","public",config('oid.sysInfo.list.sysUptime.oid'));
        return $result;
    }

    protected static function sysContact($host="")
    {
        $result = snmp2_walk("127.0.0.1","public",config('oid.sysInfo.list.sysContact.oid'));
        return $result;
    }

    protected static function SysName($host="")
    {
        $result = snmp2_walk("127.0.0.1","public",config('oid.sysInfo.list.SysName.oid'));
        return $result;

    }

    protected static function SysLocation($host="")
    {
        $result = snmp2_walk("127.0.0.1","public",config('oid.sysInfo.list.SysLocation.oid'));
        return $result;
    }

    protected static function SysService($host="")
    {
        $result = snmp2_walk("127.0.0.1","public",config('oid.sysInfo.list.SysService.oid'));
        return $result;
    }

    protected static function hrSWRunName($host="")
    {
        $result = snmp2_walk("127.0.0.1","public",config('oid.sysInfo.list.hrSWRunName.oid'));
        return $result;
    }

    protected static function hrSWInstalledName($host="")
    {
        $result = snmp2_walk("127.0.0.1","public",config('oid.sysInfo.list.hrSWInstalledName.oid'));
        return $result;
    }
}