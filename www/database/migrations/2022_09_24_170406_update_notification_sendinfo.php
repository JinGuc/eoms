<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNotificationSendinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_sendinfo', function (Blueprint $table) {
            if(!Schema::hasColumn('notification_sendinfo','sound_index'))
            {
                $table->string('sound_index')->comment('');
            }
            if(!Schema::hasColumn('notification_sendinfo','ContactId'))
            {
                $table->bigInteger('ContactId')->comment('联系人id');
            }
            if(!Schema::hasColumn('notification_sendinfo','notificationSettingId'))
            {
                $table->bigInteger('notificationSettingId')->comment('告警规则ID');
                $table->index('notificationSettingId');
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
