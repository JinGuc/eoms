<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNotificationInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_info', function (Blueprint $table) {
            if(!Schema::hasColumn('notification_info','notificationType'))
            {
                $table->tinyInteger('notificationType')->comment('告警类型 1cpu使用率 2内存使用率 3系统负载1m 4系统负载5m 5系统负载15m 6硬盘使用率');
                $table->index('notificationType');
            }
            if(!Schema::hasColumn('notification_info','notificationSettingId'))
            {
                $table->bigInteger('notificationSettingId')->comment('告警规则ID');
                $table->index('notificationSettingId');
            }
            if(!Schema::hasColumn('notification_info','stopNoticeTime'))
            {
                $table->dateTime('stopNoticeTime')->comment('停止告警时间');
            }
            if(!Schema::hasColumn('notification_info','silenceCycle'))
            {
                $table->tinyInteger('silenceCycle')->comment('间隔时长（分钟）');
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
