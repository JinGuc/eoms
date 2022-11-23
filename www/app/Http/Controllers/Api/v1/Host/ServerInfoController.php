<?php

namespace App\Http\Controllers\Api\v1\Host;

use App\Http\Controllers\Controller;
use App\Models\ServerInfo;
use Illuminate\Http\Request;

class ServerInfoController extends Controller
{
    //
    public function EChartList(Request $request)
    {
        $data = [];
        $hostId = $request->input('hostId');
        $type = $request->input('type', "1"); // 1cpu使用率 2内存使用率 3负载 4tcp连接数
        $dateType = $request->input('dateType', 1); //1分钟 2小时 3天 4月 5年
        $startTime = $request->input('startTime') ?? date('Y-m-d H:i:s',time()-600);
        $endTime = $request->input('endTime') ?? date('Y-m-d H:i:s');
        if(!$dateType) {
            $diffTime = strtotime($endTime) - strtotime($startTime);
            if($diffTime<=60) {
                $dateType = 6;
            } elseif($diffTime<=(60*60)) {
                $dateType = 1;
            } elseif($diffTime<=(60*60*24)) {
                $dateType = 2;
            } elseif($diffTime<=(60*60*24*30)) {
                $dateType = 3;
            } elseif($diffTime<=(60*60*24*30*12)) {
                $dateType = 4;
            } elseif($diffTime>(60*60*24*30*12)) {
                $dateType = 5;
            } else {
                $dateType = 1;
            }
        }
        $serverInfoObj = ServerInfo::query();
        $serverInfoObj->where("hostId", $hostId)
            ->where("created_at",">=",$startTime)
            ->where("created_at","<=",$endTime)
            ->orderBy('created_at',"DESC");
        switch ($type) {
            case "1": //cpu使用率
                $serverInfoList = $serverInfoObj->get(["cpu_use","created_at"])->toArray();
                $data = $this->arrayToEchartsByDate($serverInfoList, ["cpu_use"], $dateType);
                $data["y"] = ["cpu使用率"];
                $data["x"] = "时间";
                break;
            case "2": //内存使用率
                $serverInfoList = $serverInfoObj->get(["mem_used_percent","created_at"])->toArray();
                $data = $this->arrayToEchartsByDate($serverInfoList, ["mem_used_percent"], $dateType);
                $data["y"] = ["内存使用率"];
                $data["x"] = "时间";
                break;
            case "3": //负载
                $serverInfoList = $serverInfoObj->get(["cpu_load1","cpu_load5","cpu_load15","created_at"])->toArray();
                $data = $this->arrayToEchartsByDate($serverInfoList, ["cpu_load1","cpu_load5","cpu_load15"], $dateType);
                $data["y"] = ["一分钟负载","五分钟负载","十五分钟负载"];
                $data["x"] = "时间";
                break;
            case "4": //tcp连接数
                $serverInfoList = $serverInfoObj->get(["tcp_connect_info","created_at"])->toArray();
                $data = $this->arrayToEchartsByDate($serverInfoList, ["tcp_connect_info"], $dateType);
                $data["y"] = ["tcp连接数"];
                $data["x"] = "时间";
                break;
            case "5": //网络速度
                $serverInfoList = $serverInfoObj->get(["net_speed","created_at"])->toArray();
                $data = $this->arrayToEchartsByDate($serverInfoList, ["net_speed"], $dateType);
                $data["y"] = ["下行速度","上行速度"];
                $data["x"] = "时间";
                break;
            case "6": //磁盘id速度
                $serverInfoList = $serverInfoObj->get(["disk_io","created_at"])->toArray();
                $data = $this->arrayToEchartsByDate($serverInfoList, ["disk_io"], $dateType);
                $data["y"] = ["读取速度","写入速度"];
                $data["x"] = "时间";
                break;
            default:
                $serverInfoList = [];
                $data = [];
        }
        if(count($serverInfoList)>0)
        {
            if(count($data)>0) {
                $data["updated_at"] = date("Y-m-d H:i:s");
                return ["status"=>"success","des"=>"获取成功","res"=>$data];
            }
            return ["status"=>"success","des"=>"无数据","res"=>[]];
        }
        return ["status"=>"success","des"=>"无数据","res"=>[]];
    }
}
