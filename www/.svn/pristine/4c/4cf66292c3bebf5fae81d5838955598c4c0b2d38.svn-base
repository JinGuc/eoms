<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');


$api->version('v1',['middleware'=>['request.id']],function($api){
    $api->group(['namespace'=>'App\Http\Controllers\Auth','prefix' => 'auth'],function($api){
        $api->post('login', 'AuthController@login');
    });
    $api->group(['namespace'=>'App\Http\Controllers\Api\v1'],function($api){
        $api->post('/web/setting/list/enable','WebSetting\WebSettingController@EnableList');//启用的系统参数
    });

    $api->group(['middleware'=>['api.encryption']],function($api){
        $api->group(['namespace'=>'App\Http\Controllers\Auth','prefix' => 'auth'],function($api){
            $api->post('logout', 'AuthController@logout');
            $api->post('refresh', 'AuthController@refresh');
            $api->any('me', 'AuthController@me');
        });

        $api->group(['namespace'=>'App\Http\Controllers\Auth','prefix' => 'auth','middleware'=>['auth.check']],function($api){
            $api->post('login/history', 'AuthController@LoginHistory');
        });

        $api->group(['namespace'=>'App\Http\Controllers\Api\v1','middleware'=>['auth.check']],function($api){
            $api->post('/host/list','Host\HostController@list');//服务器列表
            $api->post('/host/save','Host\HostController@addOrEdit');//服务器添加或修改
            $api->post('/host/info','Host\HostController@info');//服务器详细信息
            $api->post('/host/del','Host\HostController@delete');//服务器删除
            $api->post('/host/get/encrypt/data','Host\HostController@getEncryptData');//获取加密的用户名和密码
            $api->post('/host/list/enable','Host\HostController@EnableList');//启用的服务器列表
            $api->post('/host/info/enable','Host\HostController@EnableInfo');//启用的服务器详情
            $api->post('/host/info/echart','Host\ServerInfoController@EChartList');//服务器图表信息
            $api->post('/host/role/list','HostRole\HostRoleController@list');//服务器角色列表
            $api->post('/host/role/index','HostRole\HostRoleController@index');//管理页面服务器角色列表
            $api->post('/host/role/save','HostRole\HostRoleController@addOrEdit');//服务器角色添加或修改
            $api->post('/host/role/info','HostRole\HostRoleController@info');//服务器角色详情
            $api->post('/host/role/del','HostRole\HostRoleController@delete');//服务器角色删除
            $api->post('/contact/list','Contact\ContactController@list');//通讯录列表
            $api->post('/contact/save','Contact\ContactController@addOrEdit');//通讯录添加或修改
            $api->post('/contact/info','Contact\ContactController@info');//通讯录详情
            $api->post('/contact/del','Contact\ContactController@delete');//通讯录删除
            $api->post('/contact/list/enable','Contact\ContactController@EnableList');//启用的通讯录列表
            $api->post('/web/setting/list','WebSetting\WebSettingController@list');//系统参数列表
            $api->post('/web/setting/save','WebSetting\WebSettingController@addOrEdit');//系统参数添加或修改
            $api->post('/notification/setting/list','Notification\NotificationSettingController@list');//告警规则列表
            $api->post('/notification/setting/save','Notification\NotificationSettingController@addOrEdit');//告警规则添加或修改
            $api->post('/notification/setting/info','Notification\NotificationSettingController@info');//告警规则添加或修改
            $api->post('/notification/setting/del','Notification\NotificationSettingController@delete');//告警规则删除
            $api->post('/notification/setting/list/enable','Notification\NotificationSettingController@EnableList');//启用的告警规则列表
            $api->post('/host/notification/list','Host\HostNotificationSettingController@list');//根据主机id或告警规则id获取已分配的告警规则
            $api->post('/host/notification/save','Host\HostNotificationSettingController@save');//添加服务器告警规则
            $api->post('/host/notification/del','Host\HostNotificationSettingController@delete');//移除服务器告警规则
            $api->post('/notification/log/list','Notification\NotificationLogController@list');//告警日志列表
            $api->post('/notification/log/incomplete','Notification\NotificationLogController@getTotal');//未处理告警总数
            $api->post('/notification/info/list','Notification\NotificationInfoController@list');//告警通知发送记录列表
            $api->post('/notification/info/byLog/list','Notification\NotificationInfoController@listByLogId');//根据告警日志id获取告警发送记录列表信息
            $api->post('/snmp/oid/list','Snmp\SnmpOidController@list');//oid列表
            $api->post('/snmp/oid/save','Snmp\SnmpOidController@addOrEdit');//oid添加或修改
            $api->post('/snmp/oid/info','Snmp\SnmpOidController@info');//oid详情
            $api->post('/snmp/oid/del','Snmp\SnmpOidController@delete');//oid删除

            $api->post('/home/resource/overview','Home\HomeController@resourceOverview');//首页资源概览
            $api->post('/home/resource/warning','Home\HomeController@resourceWarning');//首页资源预警
            $api->post('/home/resource/list','Home\HomeController@resourceList');//首页资源详情
            $api->post('/home/warning/list','Home\HomeController@warningList');//首页告警详情

            $api->post('/iptables/ip/list','Iptables\IpListController@list');//防火墙ip列表

            $api->post('/host/server/logDir/{HostId}','HostServer\HostServerController@getLogDir');// 获取服务日志目录
            $api->post('/host/server/log/{HostId}','HostServer\HostServerController@getLog');// 获取服务日志文件
            $api->post('/host/server/confDir/{HostId}','HostServer\HostServerController@getConfigDir');// 获取服务配置目录
            $api->post('/host/server/conf/{HostId}','HostServer\HostServerController@getConfig');// 获取服务配置目录

            $api->post('/host/server/py/info/{HostId}','HostServer\HostServerController@getPyServerData');// 获取服务配置目录
            $api->post('/host/server/py/server/operation/{HostId}','HostServer\HostServerController@serverOperation');// 获取服务配置目录

            $api->post('/host/server/info/{HostId}','HostServer\HostServerController@info');// 获取服务基本信息
            $api->post('/host/server/info/echart/{HostId}','HostServer\HostServerController@EChartList');// 获取服务图表信息

            $api->post('/web/viewinterface/list','WebSetting\ViewInterfaceController@list');//接口监控列表
            $api->post('/web/viewinterface/save','WebSetting\ViewInterfaceController@addOrEdit');//接口监控添加或修改
            $api->post('/web/viewinterface/del','WebSetting\ViewInterfaceController@delete');//接口监控删除
            $api->post('/web/viewinterface/detail','WebSetting\ViewInterfaceController@detail');//接口监控详情
            $api->post('/web/viewinterface/statusList','WebSetting\ViewInterfaceController@urlstatusList');//接口状态监控列表

        });
    });
});


$api->version('v2',['namespace'=>'App\Http\Controllers\Api\v2','middleware'=>['auth.check','api.encryption']],function($api){
});