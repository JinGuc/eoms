<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\testController;
use App\WebSocket\SshWebSocketHandler;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
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

Route::get('/', [Controller::class, 'HomePage']);
Route::get('/iptables_notify', [Controller::class, 'iptablesNotify']);
Route::post('/iptables_notify', [Controller::class, 'iptablesNotify']);

//供外部访问的接口
Route::group(['namespace'=>'App\Http\Controllers'],function(){
    Route::get('/test/ssh',[testController::class, 'testSsh']);//
    Route::get('/test/sys_info',[testController::class, 'sysInfo']);//
    Route::get('/test/snmp_info',[testController::class, 'snmpInfo']);//
    Route::get('/test/snmp_info2',[testController::class, 'snmpInfo2']);
    Route::get('/test/ip_list',[testController::class, 'ipList']);
    Route::get('/test/service_status',[testController::class, 'serviceStatus']);
    Route::get('/test/service_ctrl',[testController::class, 'serviceCtrl']);
    Route::get('/test/service_conncount',[testController::class, 'serviceConnCount']);
    Route::get('/test/iptables_notify', [testController::class, 'iptablesNotify']);
    Route::post('/test/iptables_notify', [testController::class, 'iptablesNotify']);
    Route::get('/test/notice_warning', [testController::class, 'noticeWarning']);
    Route::get('/test/iptables_rules', [testController::class, 'iptablesRules']);
    Route::get('/test/warningEvent', [testController::class, 'warningEvent']);
});
// WebSocketsRouter::get('/ssh-websocket/{appKey}', SshWebSocketHandler::class);
WebSocketsRouter::webSocket('/ssh-websocket/{appKey}', SshWebSocketHandler::class);