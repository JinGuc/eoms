<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserLoginLog;
use App\Models\Users;
use App\Servers\Tools\Rsa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    protected $guard = 'api';

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.check', ['except' => ['login','logout']]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse exception
     */
    public function login()
    {

        $credentials = request(['email', 'password']);
        $credentials["email"] = Rsa::deRSA_private($credentials["email"]);
        $credentials["password"] = Rsa::deRSA_private($credentials["password"]);
        $userInfo = Users::where("email", $credentials["email"])->first();
        if (! $token = $this->guard()->attempt($credentials)) {
            if($userInfo)
            {
                UserLoginLog::create([
                    "userId"=>$userInfo->id,
                    "ip"=>request()->getClientIp(),
                    "type"=>2
                ]);
            }
            else
            {
                UserLoginLog::create([
                    "userId"=>0,
                    "ip"=>request()->getClientIp(),
                    "type"=>2
                ]);
            }
            return response(['status'=>'fail','des' => 'Unauthorized','res'=>[]]);
        }
        UserLoginLog::create([
            "userId"=>$this->guard()->user()->id,
            "ip"=>request()->getClientIp(),
            "type"=>1
        ]);
        return ['status'=>'success','des' => '登录成功','res'=>$this->respondWithToken($token)];
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return ['status'=>'success','des' => '登录成功','res'=>$this->guard()->user()];
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            $this->guard()->logout();

            return ['status' => 'success', 'des' => '退出成功', 'res' => []];
        }catch (Exception $e) {
            return ['status' => 'success', 'des' => '退出成功', 'res' => ["message"=>$e->getMessage()]];
        }
    }

    /**
     * @return mixed
     */
    public function refresh()
    {
        try {
            // 刷新用户的 token
            $token = $this->guard()->refresh();
            return ['status'=>'success','des' => 'token 更新成功','res'=>$this->respondWithToken($token)];
        }
        catch (JWTException $exception)
        {
            return response()->json(["status"=>"fail","des"=>"登录超时,请重新登录","res"=>["message"=>$exception->getMessage()]],419);
        }
    }

    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @return mixed
     */
    public function guard()
    {
        return Auth::guard($this->guard);
    }


    public function LoginHistory(Request $request)
    {
        Log::info("获取服务器列表信息", ["request" => $request]);
        $page = $request->input('page', 1);
        $limit = $request->input('pagesize', 20);

        $query = UserLoginLog::query();

        $query->where("userId",$this->guard()->user()->id);
        $query->orderBy("created_at", 'DESC');

        $total = $query->count();


        $LoginHistoryList = $query->offset(($page - 1) * $limit)
            ->limit($limit)
            ->get(["ip", "type", "created_at"])
            ->toArray();


        if(count($LoginHistoryList) > 0)
        {
            $resList = array(
                'data' => $LoginHistoryList,
                'total' => $total
            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }
}

