<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationSendinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('notification_sendinfo')) {
            Schema::create('notification_sendinfo', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('hostId')->comment("服务器id");
                $table->bigInteger('notificationLogId')->comment("告警日志ID");
                $table->string('sendTo',50)->comment('通知接收人');
                $table->tinyInteger('type')->comment("类型（1电话 2短信 3邮件）");
                $table->string('content',255)->comment('');
                $table->tinyInteger('status')->comment("0-初始状态，1-成功，2-失败");
                $table->index('hostId');
                $table->index('notificationLogId');
                $table->index('type');
                $table->index('status');
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
        Schema::dropIfExists('eoms_notification_sendinfo');
    }
}
