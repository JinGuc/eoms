<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserLoginLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('user_login_log')) {
            Schema::create('user_login_log', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('userId')->comment("用户id");
                $table->ipaddress('ip')->comment("登录ip");
                $table->tinyInteger('type')->comment("类型 1登录成功 2登录失败");
                $table->index('userId');
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
