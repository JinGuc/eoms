<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUrlStatusInfo20221203 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('url_status_info', function (Blueprint $table) {
            if(!Schema::hasColumn('url_status_info','status_msg'))
            {
                $table->string('status_msg',1000)->nullable()->comment('返回状态信息描述');
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
