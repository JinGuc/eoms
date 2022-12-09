<?php

namespace App\Http\Middleware;

use App\Servers\CryptAES\CryptAES;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApiEncryption
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (is_array($response)) {
            $response["requestId"]=$request->get("requestId");
            return $response;
        }

        // 如果是导出Excel类型直接返回
        if ($response instanceof BinaryFileResponse) {
            return $response;
        }

        // 执行动作
        $oriData = $response->getOriginalContent();
        $content = json_decode($response->getContent(), true) ?? $oriData;
        $content = is_array($oriData) ? $oriData : $content;

        if(is_array($content) && array_key_exists("status",$content)) {
            foreach ($content as $key => $value) {
                $data[$key] = $value;
            }
            $data["requestId"]=$request->get("requestId");
            if ($data['status'] == "fail") {
                return $response instanceof JsonResponse ? $response->setData($data) : $response->setContent(json_encode($data));
            }
            $data["encryption"] = config('ApiEncryption.ApiEncryption');

            if (!config('ApiEncryption.ApiEncryption')) {
                return $response instanceof JsonResponse ? $response->setData($data) : $response->setContent(json_encode($data));
            }

            $Encryption = false;

            if ($content['res'] ?? []) {
                // 进行加密
                if (is_array($content['res'])) {
                    if (count($content['res']) > 0) {
                        $Encryption = true;
                    }
                } else {
                    if (is_object($content['res'])) {
                        if (method_exists($content['res'], 'count')) {
                            if ($content['res']->count() > 0) {
                                $Encryption = true;
                            }
                        } elseif (method_exists($content['res'], 'isEmpty')) {
                            if (!$content['res']->isEmpty()) {
                                $Encryption = true;
                            }
                        } elseif (method_exists($content['res'], 'first')) {
                            if ($content['res']->first()) {
                                $Encryption = true;
                            }
                        } else {
                            $Encryption = true;
                        }
                    } else if (mb_strlen($content['res']) > 0) {
                        $Encryption = true;
                    }
                }
            }

            if ($Encryption) {
                if (auth('api')->getToken()->get()) {
                    $key = substr(md5(auth('api')->getToken()->get()), 8, 16);
                    $resString = "";
                    if (!is_string($content['res'])) {
                        $resString = json_encode($content['res']);
                    }
                    $res = CryptAES::encrypt($resString, $key);
                    $data['res'] = $res;
                }
            }
            $response = $response instanceof JsonResponse ? $response->setData($data) : $response->setContent(json_encode($data));
        }
        else{
            $data = [
                "status"=>"fail",
                "des"=>array_key_exists("message",$content)?$content["message"]:"服务内部错误",
                "res"=>array_key_exists("debug",$content)?$content["debug"]:$content,
                "requestId"=>$request->get("requestId")
            ];
            $response = $response instanceof JsonResponse ? $response->setData($data) : $response->setContent(json_encode($data));
        }

        return $response;
    }
}
