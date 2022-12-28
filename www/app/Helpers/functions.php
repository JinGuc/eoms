<?php

/**
 * 判断IP地址是否合法
 * @param $ip
 * @return bool
 */
function valid_ip($ip) {
    $preg="/^((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))/";
    preg_match($preg,$ip,$matches);
    if(!empty($matches)) return true;
    return false;
}

/**
 * 存储单位换算 输入单位为 (KB)
 * @param $size
 * @param $byte
 * @return array
 */
function getSize($size, $byte=false) {
    $data = [];
    if(!$byte) {
        if ($size >= 1073741824) {
            //转成TB
            $data["size"] = round($size / 1073741824 * 100) / 100 ;
            $data["format"] = "TB";
        } elseif ($size >= 1048576) {
            //转成GB
            $data["size"] = round($size / 1048576 * 100) / 100 ;
            $data["format"] = "GB";
        } elseif ($size >= 1024) {
            //转成MB
            $data["size"] = round($size / 1024 * 100) / 100 ;
            $data["format"] = "MB";
        } else {
            //不转换直接输出
            $data["size"] = $size;
            $data["format"] = "KB";
        }
    } else {
        if ($size >= 1099511627776) {
            //转成TB
            $data["size"] = round($size / 1099511627776 * 100) / 100;
            $data["format"] = "TB";
        } elseif ($size >= 1073741824) {
            //转成GB
            $data["size"] = round($size / 1073741824 * 100) / 100;
            $data["format"] = "GB";
        } elseif ($size >= 1048576) {
            //转成MB
            $data["size"] = round($size / 1048576 * 100) / 100;
            $data["format"] = "MB";
        } elseif ($size >= 1024) {
            //转成MB
            $data["size"] = round($size / 1024 * 100) / 100;
            $data["format"] = "KB";
        } else {
            //不转换直接输出
            $data["size"] = round($size);
            $data["format"] = "byte";
        }
    }
    return $data;
}

function getSizeReverse($size,$unit, $byte=false)
{
    if(!$byte) {
        if ($unit == "TB") {
            //转成TB
            $data = ceil($size * 1073741824 / 100) * 100 ;
        } elseif ($unit == "GB") {
            //转成GB
            $data = ceil($size * 1048576 / 100) * 100 ;
        } elseif ($unit == "MB") {
            //转成MB
            $data = ceil($size * 1024 / 100) * 100 ;
        } else {
            //不转换直接输出
            $data = ceil($size);
        }
    } else {
        if ($unit == "TB") {
            //转成TB
            $data= ceil($size * 1099511627776 / 100) * 100;
        } elseif ($unit == "GB") {
            //转成GB
            $data = ceil($size * 1073741824 / 100) * 100;
        } elseif ($unit == "MB") {
            //转成MB
            $data = ceil($size * 1048576 / 100) * 100;
        } elseif ($unit == "KB") {
            //转成MB
            $data = ceil($size * 1024 / 100) * 100;
        } else {
            //不转换直接输出
            $data = ceil($size);
        }
    }
    return $data;
}
//二维数组去掉重复值
function a_array_unique($array)
{

    $out = array();
    foreach ($array as $key => $value) {

        if (!in_array($value, $out)) {

            $out[$key] = $value;
        }
    }

    $out = array_values($out);

    return $out;
}
/**
 *curl请求远程文件
 *@param string $url
 *@param string|array|payload post='a=1&b=2' or array('a':1,'b':2) or payload :json_decode($post, true);
 *@param opt:array()
 *proxy:'127.0.0.1:80',
 *gzip:false,
 *timeout:30,
 *agent:'' 不指定使用$_SERVER['HTTP_USER_AGENT'],
 *header=array('Content-Type:application/json','b:2'),
 *cookie='a=1;b=2' or array('a':1,'b':2) 不指定使用CURLOPT_COOKIEJAR,
 */
function request_by_curl($url, $post = null, $opt = [])
{
    if (function_exists("curl_init")) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if (strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (isset($opt['header'])) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $opt['header']);
        }
        if (isset($post)&&!empty($post)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($post) ? http_build_query($post) : $post);
        }
        if (isset($opt['gzip']) && $opt['gzip'] == true) {
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate,sdch');
        }
        if (isset($opt['proxy'])) {
            curl_setopt($ch, CURLOPT_PROXY, $opt['proxy']);
            //curl_setopt($ch, CURLOPT_PROXYPORT, $opt['proxy_port']); //代理服务器端口
            //curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
        }
        if (isset($opt['cookie'])) {
            curl_setopt($ch, CURLOPT_COOKIE, $opt['cookie']);
        } else {

        }
        curl_setopt($ch, CURLOPT_USERAGENT, isset($opt['agent']) ? $opt['agent'] : '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, isset($opt['timeout']) ? $opt['timeout'] : 30);
        $r = curl_exec($ch);
        curl_close($ch);
    } else {
        //throw new Exception('curl_init not exists');
        return false;
    }
    return $r;
}
/*
*
*/
function curl_get_url_http_code($url='',$timeout=60)
{
    if(empty($url)){
        return false;
    }
	$ch = curl_init($url);
	curl_setopt($ch,  CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(strpos($url,'https://')!==false){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
    }
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE); // 200
	$Header = curl_getinfo($ch);
	curl_close($ch);
    return $http_code;
}