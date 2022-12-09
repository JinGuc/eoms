<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Contact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('contact')) {
            Schema::create('contact', function (Blueprint $table) {
                $table->id();
                $table->string('name',100)->comment("姓名");
                $table->string('phone',100)->comment("手机号");
                $table->string('email',100)->comment("邮箱");
                $table->tinyInteger('status')->default('1')->comment("状态 1启用 2禁用");
                $table->index('status');
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
