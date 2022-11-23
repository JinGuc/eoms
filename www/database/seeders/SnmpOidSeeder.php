<?php

namespace Database\Seeders;

use App\Models\SnmpOid;
use Illuminate\Database\Seeder;

class SnmpOidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SnmpOid::insert([
            ["serverName"=> 'httpd', "serverType"=>'status', "oid"=>'.1.3.6.1.4.1.2021.1001', "type"=>1],
            ["serverName"=> 'php-fpm', "serverType"=>'status', "oid"=>'.1.3.6.1.4.1.2021.1002', "type"=>1],
            ["serverName"=> 'nginx', "serverType"=>'status', "oid"=>'.1.3.6.1.4.1.2021.1003', "type"=>1],
            ["serverName"=> 'redis', "serverType"=>'status', "oid"=>'.1.3.6.1.4.1.2021.1004', "type"=>1],
            ["serverName"=> 'java', "serverType"=>'status', "oid"=>'.1.3.6.1.4.1.2021.1005', "type"=>1],
            ["serverName"=> 'ntpd', "serverType"=>'status', "oid"=>'.1.3.6.1.4.1.2021.1006', "type"=>1],
            ["serverName"=> 'mysql', "serverType"=>'status', "oid"=>'.1.3.6.1.4.1.2021.1007', "type"=>1],
            ["serverName"=> 'docker', "serverType"=>'status', "oid"=>'.1.3.6.1.4.1.2021.1008', "type"=>1],
            ["serverName"=> 'sshd', "serverType"=>'status', "oid"=>'.1.3.6.1.4.1.2021.1009', "type"=>1],
            ["serverName"=> 'iptables', "serverType"=>'status', "oid"=>'.1.3.6.1.4.1.2021.1010', "type"=>1],
            ["serverName"=> 'snmpd', "serverType"=>'status', "oid"=>'.1.3.6.1.4.1.2021.1011', "type"=>1],
            ["serverName"=> 'httpd', "serverType"=>'restart', "oid"=>'.1.3.6.1.4.1.2021.1101', "type"=>1],
            ["serverName"=> 'php-fpm', "serverType"=>'restart', "oid"=>'.1.3.6.1.4.1.2021.1102', "type"=>1],
            ["serverName"=> 'nginx', "serverType"=>'restart', "oid"=>'.1.3.6.1.4.1.2021.1103', "type"=>1],
            ["serverName"=> 'redis', "serverType"=>'restart', "oid"=>'.1.3.6.1.4.1.2021.1104', "type"=>1],
            ["serverName"=> 'java', "serverType"=>'restart', "oid"=>'.1.3.6.1.4.1.2021.1105', "type"=>1],
            ["serverName"=> 'ntpd', "serverType"=>'restart', "oid"=>'.1.3.6.1.4.1.2021.1106', "type"=>1],
            ["serverName"=> 'mysql', "serverType"=>'restart', "oid"=>'.1.3.6.1.4.1.2021.1107', "type"=>1],
            ["serverName"=> 'docker', "serverType"=>'restart', "oid"=>'.1.3.6.1.4.1.2021.1108', "type"=>1],
            ["serverName"=> 'sshd', "serverType"=>'restart', "oid"=>'.1.3.6.1.4.1.2021.1109', "type"=>1],
            ["serverName"=> 'iptables', "serverType"=>'restart', "oid"=>'.1.3.6.1.4.1.2021.1110', "type"=>1],
            ["serverName"=> 'snmpd', "serverType"=>'restart', "oid"=>'.1.3.6.1.4.1.2021.1111', "type"=>1],
            ["serverName"=> 'httpd', "serverType"=>'start', "oid"=>'.1.3.6.1.4.1.2021.1201', "type"=>1],
            ["serverName"=> 'php-fpm', "serverType"=>'start', "oid"=>'.1.3.6.1.4.1.2021.1202', "type"=>1],
            ["serverName"=> 'nginx', "serverType"=>'start', "oid"=>'.1.3.6.1.4.1.2021.1203', "type"=>1],
            ["serverName"=> 'redis', "serverType"=>'start', "oid"=>'.1.3.6.1.4.1.2021.1204', "type"=>1],
            ["serverName"=> 'java', "serverType"=>'start', "oid"=>'.1.3.6.1.4.1.2021.1205', "type"=>1],
            ["serverName"=> 'ntpd', "serverType"=>'start', "oid"=>'.1.3.6.1.4.1.2021.1206', "type"=>1],
            ["serverName"=> 'mysql', "serverType"=>'start', "oid"=>'.1.3.6.1.4.1.2021.1207', "type"=>1],
            ["serverName"=> 'docker', "serverType"=>'start', "oid"=>'.1.3.6.1.4.1.2021.1208', "type"=>1],
            ["serverName"=> 'sshd', "serverType"=>'start', "oid"=>'.1.3.6.1.4.1.2021.1209', "type"=>1],
            ["serverName"=> 'iptables', "serverType"=>'start', "oid"=>'.1.3.6.1.4.1.2021.1210', "type"=>1],
            ["serverName"=> 'snmpd', "serverType"=>'start', "oid"=>'.1.3.6.1.4.1.2021.1211', "type"=>1],
            ["serverName"=> 'httpd', "serverType"=>'stop', "oid"=>'.1.3.6.1.4.1.2021.1301', "type"=>1],
            ["serverName"=> 'php-fpm', "serverType"=>'stop', "oid"=>'.1.3.6.1.4.1.2021.1302', "type"=>1],
            ["serverName"=> 'nginx', "serverType"=>'stop', "oid"=>'.1.3.6.1.4.1.2021.1303', "type"=>1],
            ["serverName"=> 'redis', "serverType"=>'stop', "oid"=>'.1.3.6.1.4.1.2021.1304', "type"=>1],
            ["serverName"=> 'java', "serverType"=>'stop', "oid"=>'.1.3.6.1.4.1.2021.1305', "type"=>1],
            ["serverName"=> 'ntpd', "serverType"=>'stop', "oid"=>'.1.3.6.1.4.1.2021.1306', "type"=>1],
            ["serverName"=> 'mysql', "serverType"=>'stop', "oid"=>'.1.3.6.1.4.1.2021.1307', "type"=>1],
            ["serverName"=> 'docker', "serverType"=>'stop', "oid"=>'.1.3.6.1.4.1.2021.1308', "type"=>1],
            ["serverName"=> 'sshd', "serverType"=>'stop', "oid"=>'.1.3.6.1.4.1.2021.1309', "type"=>1],
            ["serverName"=> 'iptables', "serverType"=>'stop', "oid"=>'.1.3.6.1.4.1.2021.1310', "type"=>1],
            ["serverName"=> 'snmpd', "serverType"=>'stop', "oid"=>'.1.3.6.1.4.1.2021.1311', "type"=>1],
            ["serverName"=> 'httpd', "serverType"=>'conncount', "oid"=>'.1.3.6.1.4.1.2021.301', "type"=>1],
            ["serverName"=> 'php-fpm', "serverType"=>'conncount', "oid"=>'.1.3.6.1.4.1.2021.302', "type"=>1],
            ["serverName"=> 'nginx', "serverType"=>'conncount', "oid"=>'.1.3.6.1.4.1.2021.303', "type"=>1],
            ["serverName"=> 'redis', "serverType"=>'conncount', "oid"=>'.1.3.6.1.4.1.2021.304', "type"=>1],
            ["serverName"=> 'java', "serverType"=>'conncount', "oid"=>'.1.3.6.1.4.1.2021.305', "type"=>1],
            ["serverName"=> 'ntpd', "serverType"=>'conncount', "oid"=>'.1.3.6.1.4.1.2021.306', "type"=>1],
            ["serverName"=> 'mysql', "serverType"=>'conncount', "oid"=>'.1.3.6.1.4.1.2021.307', "type"=>1],
            ["serverName"=> 'docker', "serverType"=>'conncount', "oid"=>'.1.3.6.1.4.1.2021.308', "type"=>1],
            ["serverName"=> 'sshd', "serverType"=>'conncount', "oid"=>'.1.3.6.1.4.1.2021.309', "type"=>1],
            ["serverName"=> 'snmpd', "serverType"=>'conncount', "oid"=>'.1.3.6.1.4.1.2021.310', "type"=>1],
            ["serverName"=> 'php', "serverType"=>'conncount', "oid"=>'.1.3.6.1.4.1.2021.311', 1]
        ]);
        $date = date("Y-m-d H:i:s");
        SnmpOid::whereNull("created_at")->update(["created_at"=>$date,"updated_at"=>$date]);
    }
}
