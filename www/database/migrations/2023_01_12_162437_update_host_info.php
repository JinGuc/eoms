<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHostInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('snmp_host', function (Blueprint $table) {
            if(!Schema::hasColumn('snmp_host','ssh_port'))
            {
                $table->integer('ssh_port')->default(0)->comment('ssh 端口');
            }
            if(!Schema::hasColumn('snmp_host','ssh_connect_secret'))
            {
                $table->string('ssh_connect_secret',20)->default('')->comment('ssh 连接口令');
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
