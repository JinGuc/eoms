<?php

namespace App\Http\Controllers\Api\v1\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HostSshController extends Controller
{
    //

    public function createConnectionId(Request $request)
    {
        return ["status" => "success", "des" => '获取成功', 'res'=>["data"=>bin2hex(openssl_random_pseudo_bytes(12))]];
    }
}
