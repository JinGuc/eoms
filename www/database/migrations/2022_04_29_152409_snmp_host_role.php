<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SnmpHostRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('snmp_host_role')) {
            Schema::create('snmp_host_role', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('hostId')->comment("服务器id");
                $table->string('type')->comment("服务器类型");
                $table->index('hostId');
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
