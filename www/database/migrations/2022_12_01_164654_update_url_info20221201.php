<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUrlInfo20221201 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('url_info', function (Blueprint $table) {
            if(!Schema::hasColumn('url_info','db_username'))
            {
                $table->string('db_username','50')->nullable()->default('')->comment('数据库账号');  
            }
            if(!Schema::hasColumn('url_info','db_password'))
            {
                $table->string('db_password','50')->nullable()->default('')->comment('数据库密码');  
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
