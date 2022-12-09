<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IpList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('ip_list')) {
            Schema::create('ip_list', function (Blueprint $table) {
                $table->id();
                $table->ipAddress('start_ip')->comment("起始ip");
                $table->ipAddress('end_ip')->comment("截至ip");
                $table->string('ip')->comment("ip地址带子网掩码");
                $table->tinyInteger('is_china')->comment("是否中国ip")->default('1');
                $table->string('country')->comment("国家");
                $table->string('note')->comment("描述");
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
