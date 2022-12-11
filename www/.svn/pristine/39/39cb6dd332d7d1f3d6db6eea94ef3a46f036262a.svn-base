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
<<<<<<< HEAD
                $table->string('response_time','30')->nullable()->default('')->comment('响应时长（单位s）');  
=======
                $table->string('response_time','30')->nullable()->comment('响应时长（单位s）');  
>>>>>>> 9a1ad94cb8ad48aafebee8b5b06a665c23f572ee
            }
            if(!Schema::hasColumn('url_info','gathering_time'))
            {
                $table->dateTime('gathering_time')->nullable()->nullable()->comment('最后采集时间');  
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
