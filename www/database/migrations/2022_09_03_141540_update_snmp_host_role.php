<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSnmpHostRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('snmp_host_role', function (Blueprint $table) {
            if(!Schema::hasColumn('snmp_host_role','running'))
            {
                $table->tinyInteger('running')->comment('运行状态 1运行中 0停止');
            }
            if(!Schema::hasColumn('snmp_host_role','heartNum'))
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
