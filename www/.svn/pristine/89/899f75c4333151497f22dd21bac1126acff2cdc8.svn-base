<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Controller@HomePage');
Route::get('/iptables_notify', 'Controller@iptablesNotify');
Route::post('/iptables_notify', 'Controller@iptablesNotify');

//供外部访问的接口
Route::group(['namespace'=>'App\Http\Controllers'],function(){
    Route::get('/test/sys_info','testController@sysInfo');//
    Route::get('/test/snmp_info','testController@snmpInfo');//
    Route::get('/test/snmp_info2','testController@snmpInfo2');
    Route::get('/test/ip_list','testController@ipList');
    Route::get('/test/service_status','testController@serviceStatus');
    Route::get('/test/service_ctrl','testController@serviceCtrl');
    Route::get('/test/service_conncount','testController@serviceConnCount');
    Route::get('/test/iptables_notify', 'testController@iptablesNotify');
    Route::post('/test/iptables_notify', 'testController@iptablesNotify');
    Route::get('/test/notice_warning', 'testController@noticeWarning');
    Route::get('/test/iptables_rules', 'testController@iptablesRules');
    Route::get('/test/warningEvent', 'testController@warningEvent');
});
