<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SnmpRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('snmp_role')) {
            Schema::create('snmp_role', function (Blueprint $table) {
                $table->id();
                $table->string('name',255)->comment("服务名称");
                $table->string('key',50)->comment("服务对应的key");
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
