<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUpdateNotificationInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_info', function (Blueprint $table) {
            if(!Schema::hasColumn('notification_info','hostId'))
            {
                $table->integer('hostId')->comment('主机id');
                $table->index('hostId');
            }
            if(!Schema::hasColumn('notification_info','sound_index'))
            {
                $table->string('sound_index')->comment('语音索引');
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
