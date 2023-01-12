<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    //
    protected $version = "1.0.3";
    public function versionCheck(Request $request)
    {
        $version = $request->header("JgEomsApp");
        if($version) {
            return [
                "status" => 200,
                "msg" => "ok",
                "data" => [
                    "version" => $this->version,
                    "description" => "1. 优化用户体验。 \n2. 修复已知bug。",
                    "url" => "https://eoms.jinguc.com/app/download_new",
                ]
            ];
        }
        else {
            abort(404);
        }
    }

    public function downloadNew(Request $request)
    {
        $version = $request->header("JgEomsApp");
        if($version && file_exists(public_path('app-release.apk'))) {
            return response()->streamDownload(function () {
                echo file_get_contents(public_path('app-release.apk'));;
            }, $this->version.".apk",[
                'Content-Type' => 'application/vnd.android.package-archive',
            ]);
        }
        else {
            abort(404);
        }
    }
}
