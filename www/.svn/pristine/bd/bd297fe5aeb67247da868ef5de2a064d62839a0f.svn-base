<?php


namespace App\Models;

use App\Models\Traits\DateType;
use App\Models\Traits\Utils;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    use DateType,Utils;

    //公共状态
    public static $_status = array(
        'on' => array(
            'code' => '1',
            'text' => '启用',
        ),
        'off' => array(
            'code' => '2',
            'text' => '停用',
        ),
    );
}