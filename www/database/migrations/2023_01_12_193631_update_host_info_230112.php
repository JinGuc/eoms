<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHostInfo230112 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('snmp_host', function (Blueprint $table) {
            if(Schema::hasColumn('snmp_host','username'))
            {
                $table->text('username')->nullable()->default('')->comment("用户名")->change();
            }
            if(Schema::hasColumn('snmp_host','password'))
            {
                $table->text('password')->nullable()->default('')->comment("密码")->change();
            }
            if(Schema::hasColumn('snmp_host','ssh_connect_secret'))
            {
                $table->text('ssh_connect_secret')->default('')->comment('ssh 连接口令')->change();
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
