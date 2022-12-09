<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUpdateNotificationLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_log', function (Blueprint $table) {
            if(!Schema::hasColumn('notification_log','sendType'))
            {
                $table->tinyInteger('sendType')->comment('');
            }
            if(!Schema::hasColumn('notification_log','sound_index'))
            {
                $table->string('sound_index')->comment('');
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
