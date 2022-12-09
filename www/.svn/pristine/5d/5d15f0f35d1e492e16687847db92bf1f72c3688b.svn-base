<?php

namespace  App\Models\Traits;

trait Utils
{
    /*
    * @des:通过code获取对应的text值
    * @param: 自定义的二维数组
    * @return: 对应的text值
    */
    public static function getTextByCode($data, $code)
    {
        if(!empty($data)){
            foreach($data as $key => $val){
                if($val['code'] == $code){
                    return $val['text'];
                }
            }
        }
        return '';
    }
}