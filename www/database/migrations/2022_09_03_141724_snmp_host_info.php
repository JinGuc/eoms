<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SnmpHostInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('snmp_host_info')) {
            Schema::create('snmp_host_info', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('hostId')->comment("服务器id");
                $table->bigInteger('roleId')->comment("服务器角色id");
                $table->decimal('cpu_use',10,2)->comment("cpu使用率");
                $table->string('memory_use',255)->comment("已使用内存量,单位（M）");
                $table->text('connect_info')->comment("连接数");
                $table->datetime('runtime')->comment("运行时间");
                $table->index("hostId");
                $table->index("roleId");
                $table->index('created_at');
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
