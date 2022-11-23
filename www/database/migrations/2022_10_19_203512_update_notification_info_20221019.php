<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNotificationInfo20221019 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_info', function (Blueprint $table) {
            if(!Schema::hasColumn('notification_info','relate_table'))
            {
                $table->string('relate_table',50)->comment('关联表名');
            }
            if(!Schema::hasColumn('notification_info','relate_id'))
            {
                $table->integer('relate_id')->comment('关联表名ID');
                $table->index(['relate_table','relate_id']);
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
