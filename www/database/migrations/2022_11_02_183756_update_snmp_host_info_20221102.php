<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSnmpHostInfo20221102 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('snmp_host_info', function (Blueprint $table) {
            if(!Schema::hasColumn('snmp_host_info','status'))
            {
                $table->string('status',20)->default('')->default('stop')->comment("状态");
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
