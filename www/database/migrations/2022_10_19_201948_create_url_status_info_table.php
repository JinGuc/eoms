<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlStatusInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('url_status_info')) {
            Schema::create('url_status_info', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('url_id')->comment("接口地址id");
                $table->string('url',255)->comment("接口地址");
                $table->string('status_code',20)->comment("接口返回状态码");
                $table->index('url_id');
                $table->index('created_at');
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
        Schema::dropIfExists('url_status_info');
    }
}
