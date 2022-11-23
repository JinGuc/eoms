<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SnmpOid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('snmp_oid')) {
            Schema::create('snmp_oid', function (Blueprint $table) {
                $table->id();
                $table->string('serverName',255)->comment("服务名称");
                $table->string('serverType',50)->comment("监控名称");
                $table->string('oid',50)->comment("snmp-oid");
                $table->tinyInteger('type')->comment("类型（1-系统内置，2-系统自动生成）");
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
