<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUrlInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('url_info', function (Blueprint $table) {
            if(!Schema::hasColumn('url_info','timeout'))
            {
                $table->tinyInteger('timeout')->default('60')->comment('超时时长');   
            }
            if(!Schema::hasColumn('url_info','response_time'))
            {
                $table->string('response_time','30')->nullable()->default('')->comment('响应时长（单位s）');   
            }
            if(!Schema::hasColumn('url_info','gathering_time'))
            {
                $table->dateTime('gathering_time')->nullable()->default('')->comment('最后采集时间');  
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
