<?php


namespace App\Servers\snmp;


use Exception;

class snmpCheck
{
    public static function check($hostIp)
    {
        try {
            $result = snmp2_walk($hostIp, config('snmp.rocommunity'), config('oid.sysInfo.list.sysDescr.oid'), config('snmp.timeout'));
            if(count($result)>0) {
                return true;
            }
            return false;
        }
        catch(Exception $e) {
            return false;
        }
    }
}