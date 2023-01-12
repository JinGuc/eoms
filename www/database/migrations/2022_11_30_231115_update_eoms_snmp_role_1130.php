<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEomsSnmpRole1130 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('snmp_role', function (Blueprint $table) {
            if(!Schema::hasColumn('snmp_role','user'))
            {
                $table->string('user','100')->nullable()->default('')->comment('授权用户'); 
            }
            if(!Schema::hasColumn('snmp_role','password'))
            {
                $table->string('password','200')->nullable()->default('')->comment('授权密码'); 
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
