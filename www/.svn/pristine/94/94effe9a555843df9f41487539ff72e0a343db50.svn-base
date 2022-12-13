<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEomsSnmpHostInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('snmp_host_info', function (Blueprint $table) {
            if(!Schema::hasColumn('snmp_host_info','es_health_info'))
            {
                $table->longText('es_health_info')->nullable()->comment('es健康监控信息'); 

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
