<?php
namespace App\Servers\DbLog;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * 数据库日志
 *  class DBLog
 */
class DbLog
{
    /**
     * 运行函数 需要在 AppServiceProvider 的boot中运行
     * function run
     */
    public static function run()
    {
        $sql_debug = config('database.debug'); //获取数据库debug开关配置 默认在非正式环境下 APP_ENV！=production 为true
        if ($sql_debug)
        {
            DB::listen(function ($sql){
                foreach ($sql->bindings as $i => $binding)
                {
                    if ($binding instanceof \DateTime)
                    {
                        $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    }
                    else
                    {
                        if (is_string($binding))
                        {
                            $sql->bindings[$i] = "'$binding'";
                        }
                    }
                }
                $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
                $query = vsprintf($query, $sql->bindings);
                Log::channel('sql')->debug('运行SQL',["query"=>$query,"time"=>$sql->time . ' ms']);
            });
        }
    }
}
