<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUrlInfo20221117 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('url_info', function (Blueprint $table) {
            if(!Schema::hasColumn('url_info','response_time'))
            {
<<<<<<< HEAD
                $table->string('response_time','30')->nullable()->default('')->comment('响应时长（单位s）');  
=======
                $table->string('response_time','30')->nullable()->comment('响应时长（单位s）');  
>>>>>>> 9a1ad94cb8ad48aafebee8b5b06a665c23f572ee
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
