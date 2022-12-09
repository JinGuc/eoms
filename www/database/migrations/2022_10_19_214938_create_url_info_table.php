<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('url_info')) {
            Schema::create('url_info', function (Blueprint $table) {
                $table->id();
                $table->string('title',200)->comment("接口名称");
                $table->string('url',300)->comment("接口地址");
                $table->string('port',8)->comment("端口号");
                $table->tinyInteger('status')->comment("状态 1启用 2禁用")->default('1');
                $table->string('type')->comment("协议类型");
                $table->string('remark',300)->nullable()->comment("备注");
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
        Schema::dropIfExists('url_info');
    }
}
