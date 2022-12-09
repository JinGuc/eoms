<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUrlInfo20221023 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('url_info', function (Blueprint $table) {
            if(!Schema::hasColumn('url_info','running'))
            {
                $table->tinyInteger('running')->default('0')->comment('运行状态 1正常 0异常');
            }
            if(!Schema::hasColumn('url_info','heartNum'))
            {
                $table->tinyInteger('heartNum')->default('0')->comment("已获取在线状态次数");
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
