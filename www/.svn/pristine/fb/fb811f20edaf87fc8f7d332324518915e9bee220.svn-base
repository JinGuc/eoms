<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use App\Events\Snmp\SysInfoEvent;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function HomePage()
    {
        if(file_exists(public_path().'/index.html')) {
            View::addExtension('html', 'php');
            return view()->file(public_path() . '/index.html');
        }
        return view('welcome');
    }
    /**
     * 数组转echarts图表
     * @param $array
     * @param $indexKey
     * @param $dateType 1分钟 2小时 3天 4月 5年
     * @return array
     */
    protected function arrayToEchartsByDate($array, $indexKey, $dateType)
    {
        $data = [];
        $info = [];
        $index = [];
        foreach ($array as $key => $value) {
            if($dateType=="1") {
                $date = date("Y-m-d H:i",strtotime($value["created_at"]));
            } else if($dateType=="2") {
                $date = date("Y-m-d H",strtotime($value["created_at"]));
            } else if($dateType=="3") {
                $date = date("Y-m-d",strtotime($value["created_at"]));
            } else if($dateType=="4") {
                $date = date("Y-m",strtotime($value["created_at"]));
            } else if($dateType=="5") {
                $date = date("Y",strtotime($value["created_at"]));
            } else if($dateType=="6") {
                $date = date("Y-m-d H:i:s",strtotime($value["created_at"]));
            } else {
                $date = date("Y-m-d",strtotime($value["created_at"]));
            }
            foreach($indexKey as $k=>$v)
            {
                if($v=="tcp_connect_info") {
                    $tmp = json_decode($value[$v],true);
                    if(!array_key_exists($date,$info)) {
                        $info[$date][$v] = $tmp["TCP_TOTAL"];
                        $index[$date][$v] = 1;
                    } else {
                        if(!array_key_exists($v,$info[$date])) {
                            $info[$date][$v] = $tmp["TCP_TOTAL"];
                            $index[$date][$v] = 1;
                        } else {
                            $info[$date][$v] += $tmp["TCP_TOTAL"];
                            $index[$date][$v]++;
                        }
                    }
                }
                elseif($v=="connect_info") {
                    $tmp = json_decode($value[$v],true);
                    if(!array_key_exists($date,$info)) {
                        $info[$date][$v] = $tmp["CONNTOTAL"];
                        $index[$date][$v] = 1;
                    } else {
                        if(!array_key_exists($v,$info[$date])) {
                            $info[$date][$v] = $tmp["CONNTOTAL"];
                            $index[$date][$v] = 1;
                        } else {
                            $info[$date][$v] += $tmp["CONNTOTAL"];
                            $index[$date][$v]++;
                        }
                    }
                }
                elseif($v=="net_speed") {
                    $tmp = json_decode($value[$v],true);
                    if(!array_key_exists($date,$info)) {
                        $info[$date][$v]["in"] = $tmp["in"];
                        $index[$date][$v]["in"] = 1;
                        $info[$date][$v]["out"] = $tmp["out"];
                        $index[$date][$v]["out"] = 1;
                    } else {
                        if(!array_key_exists($v,$info[$date])) {
                            $info[$date][$v]["in"] = $tmp["in"];
                            $index[$date][$v]["in"] = 1;
                            $info[$date][$v]["out"] = $tmp["out"];
                            $index[$date][$v]["out"] = 1;
                        } else {
                            $info[$date][$v]["in"] += $tmp["in"];
                            $index[$date][$v]["in"]++;
                            $info[$date][$v]["out"] += $tmp["out"];
                            $index[$date][$v]["out"]++;
                        }
                    }
                }
                elseif($v=="disk_io") {
                    $tmp = json_decode($value[$v],true);
                    foreach ($tmp as $vvv)
                    {
                        $exists = true;
                        if(!array_key_exists($date,$info)) {
                            $exists = false;
                        }
                        else {
                            if(!array_key_exists($vvv['disk_name'],$info[$date])) {
                                $exists = false;
                            } else {
                                if(!array_key_exists($v,$info[$date][$vvv['disk_name']])) {
                                    $exists = false;
                                }
                            }
                        }
                        if(!$exists) {
                            $info[$date][$vvv['disk_name']][$v]["r"] = $vvv["kB_read_avg"];
                            $index[$date][$vvv['disk_name']][$v]["r"] = 1;
                            $info[$date][$vvv['disk_name']][$v]["w"] = $vvv["kB_wrtn_avg"];
                            $index[$date][$vvv['disk_name']][$v]["w"] = 1;
                        }
                        else {
                            $info[$date][$vvv['disk_name']][$v]["r"] += $vvv["kB_read_avg"];
                            $index[$date][$vvv['disk_name']][$v]["r"]++;
                            $info[$date][$vvv['disk_name']][$v]["w"] += $vvv["kB_wrtn_avg"];
                            $index[$date][$vvv['disk_name']][$v]["w"]++;
                        }
                    }
                }
                else {
                    if(!array_key_exists($date,$info)) {
                        $info[$date][$v] = $value[$v];
                        $index[$date][$v] = 1;
                    } else {
                        if(!array_key_exists($v,$info[$date])) {
                            $info[$date][$v] = $value[$v];
                            $index[$date][$v] = 1;
                        } else {
                            $info[$date][$v] += $value[$v];
                            $index[$date][$v]++;
                        }
                    }
                }
            }
        }
        foreach ($indexKey as $key => $value)
        {
            if($value=="net_speed") {
                foreach ($info as $k => $v) {
                    $data["data"][$key][] = number_format($v[$value]["in"] / $index[$k][$value]["in"], 2,".","");
                    $data["data"][$key+1][] = number_format($v[$value]["out"] / $index[$k][$value]["out"], 2,".","");
                }
            }
            else if($value=="disk_io") {
                foreach ($info as $k => $v) {
                    foreach ($v as $kk => $vv) {
                        $data["data"][$kk][$key][] = number_format($vv[$value]["r"] / $index[$k][$kk][$value]["r"], 2, ".", "");
                        $data["data"][$kk][$key + 1][] = number_format($vv[$value]["w"] / $index[$k][$kk][$value]["w"], 2, ".", "");
                    }
                }
            }
            else {
                foreach ($info as $k => $v) {
                    $data["data"][$key][] = number_format($v[$value] / $index[$k][$value], 2,".","");
                }
            }
        }
        foreach($info as $k=>$v) {
            $data["header"][] = $k;
        }
        return $data;
    }

    /**
     * 格式化磁盘详情
     * @param $diskInfo
     * @return array|mixed
     */
    protected function diskInfoFormat($diskInfo)
    {
        if(!is_array($diskInfo))
        {
            $diskInfo = json_decode($diskInfo, true);
        }
        foreach($diskInfo as $key => $value)
        {
            if(array_key_exists("disk_size", $value))
            {
                $diskInfo[$key]["disk_size"] = getSize($value["disk_size"]);
            }
            if(array_key_exists("disk_used_size", $value))
            {
                $diskInfo[$key]["disk_used_size"] = getSize($value["disk_used_size"]);
            }
        }
        return $diskInfo;
    }

    /**
     * 挂载点详情
     * @param $storageInfo
     * @return array|mixed
     */
    protected function storageInfoFormat($storageInfo)
    {
        if(!is_array($storageInfo))
        {
            $storageInfo = json_decode($storageInfo, true);
        }
        foreach($storageInfo as $key => $value)
        {
            if(array_key_exists("total", $value))
            {
                $storageInfo[$key]["total"] = getSize($value["total"]);
            }
            if(array_key_exists("used", $value))
            {
                $storageInfo[$key]["used"] = getSize($value["used"]);
            }
        }
        return $storageInfo;
    }

    /**
     * 网络详情
     * @param $ifInfo
     * @return array|mixed
     */
    protected function ifInfoFormat($ifInfo)
    {
        if(!is_array($ifInfo))
        {
            $ifInfo = json_decode($ifInfo, true);
        }
        foreach($ifInfo as $key => $value)
        {
            $ifInfo[$key] = getSize($value,true);
        }
        return $ifInfo;
    }

    /**
     * 磁盘详情
     * @param $ioInfo
     * @return array|mixed
     */
    protected function ioInfoFormat($ioInfo)
    {
        if(!is_array($ioInfo))
        {
            $ioInfo = json_decode($ioInfo, true);
        }
        foreach($ioInfo as $key => $value)
        {
            if (array_key_exists("kB_read_avg", $value)) {
                $ioInfo[$key]["kB_read_avg"] = getSize($value["kB_read_avg"]);
            }
            if (array_key_exists("kB_wrtn_avg", $value)) {
                $ioInfo[$key]["kB_wrtn_avg"] = getSize($value["kB_wrtn_avg"]);
            }
        }
        return $ioInfo;
    }
}
