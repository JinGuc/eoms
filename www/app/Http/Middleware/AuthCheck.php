<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AuthCheck extends BaseMiddleware
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
        // 检查此次请求中是否带有 token，如果没有则抛出异常。
        try
        {
            $this->checkForToken($request);
            if ($this->auth->parseToken()->authenticate())
            {
                return $next($request);
            }
        }
        catch (Exception $exception)
        {
            if($exception instanceof TokenExpiredException)
            {
                try {
                    // 刷新用户的 token
                    $token = $this->auth->refresh();
                    $expires = $this->auth->factory()->getTTL() * 60;
                    // 使用一次性登录以保证此次请求的成功
                    Auth::guard('api')->onceUsingId($this->auth->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray()['sub']);
                    return $next($request)->withHeaders([
                        'expires'=> config('jwt.refresh_ttl')?config('jwt.refresh_ttl') * 60:$expires,
                        'Authorization'=> 'jinGuEoms/'.$token,
                        'Access-Control-Expose-Headers'=> 'Authorization'
                    ]);
                }
                catch (JWTException $exception)
                {
                    return response()->json(["status"=>"fail","des"=>"登录超时,请重新登录","res"=>["message"=>$exception->getMessage()]],419);
                    // 如果捕获到此异常，即代表 refresh 也过期了，用户无法刷新令牌，需要重新登录。
                    // throw new UnauthorizedHttpException('jwt-auth', $exception->getMessage());
                }
            }
            else
            {
                return response()->json(["status"=>"fail","des"=>"用户未登录","res"=>["message"=>$exception->getMessage()]],401);
            }
        }
    }
}
