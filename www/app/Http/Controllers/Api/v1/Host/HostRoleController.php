<?php

namespace App\Http\Controllers\Api\v1\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HostRoleController extends Controller
{
    /**
     * 服务器可选角色列表
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $data = config('hostRole');
        if(count($data)>0)
        {
            return ["status"=>"success", "des"=>"获取成功","res"=>$data];
        }
        return ["status"=>"fail", "des"=>"未设置角色","res"=>[]];
    }
}
