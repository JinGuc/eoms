<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HostNotificationSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('host_notification_setting')) {
            Schema::create('host_notification_setting', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('hostId')->comment("服务器id");
                $table->bigInteger('notificationId')->comment("告警设置id");
                $table->index('hostId');
                $table->index('notificationId');
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
