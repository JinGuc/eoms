<?php

namespace App\Http\Controllers\Api\v1\Iptables;

use App\Http\Controllers\Controller;
use App\Models\ipList;
use Illuminate\Http\Request;

class IpListController extends Controller
{
    /**
     * ip地址段列表
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('pagesize', 20);

        $query = ipList::query();
        $query->orderBy("created_at", 'DESC');
        $total = $query->count();


        $HostList = $query->offset(($page - 1) * $limit)
            ->limit($limit)
            ->get()
            ->toArray();

        if(count($HostList) > 0)
        {
            $resList = array(
                'data' => $HostList,
                'total' => $total
            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }
}
