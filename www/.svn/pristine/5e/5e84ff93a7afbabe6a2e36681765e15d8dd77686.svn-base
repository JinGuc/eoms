<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SnmpHost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('snmp_host')) {
            Schema::create('snmp_host', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment("服务器名称");
                $table->ipAddress('host')->comment("服务器地址");
                $table->tinyInteger('type')->default('1')->comment("设备类型 1服务器");
                $table->tinyInteger('status')->default('1')->comment("状态 1启用 2禁用");
                $table->tinyInteger('running')->default('0')->comment("在线状态 1在线 0离线");
                $table->tinyInteger('heartNum')->default('0')->comment("已获取在线状态次数");
                $table->string('username')->nullable()->comment("用户名");
                $table->string('password')->nullable()->comment("密码");
                $table->index("status");
                $table->index("type");
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
