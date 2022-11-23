<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WebSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('web_setting')) {
            Schema::create('web_setting', function (Blueprint $table) {
                $table->id();
                $table->string('fd_name')->comment("字段名");
                $table->tinyInteger('status')->comment("状态 1启用 2禁用")->default('1');
                $table->tinyInteger('type')->comment("类型 1前端使用 2后端使用")->default('1');
                $table->string('value',500)->comment("参数值");
                $table->string('note',500)->comment("描述");
                $table->index('status');
                $table->index('fd_name');
                $table->index('type');
                $table->timestamps();
            });
        }
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
