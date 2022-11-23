<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('notification_info')) {
            Schema::create('notification_info', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('notificationLogId')->comment("告警日志id");
                $table->tinyInteger('sendType')->comment("通知方式 1电话 2短信 3邮件 4电话+短信 5电话+邮件 6短信+邮件 7电话+短信+邮件 ");
                $table->bigInteger('ContactId')->comment("通讯录id");
                $table->longText('data')->comment("通知内容");
                $table->tinyInteger('status')->comment("通知状态 0未处理 1成功 2失败")->default(0);
                $table->index('notificationLogId');
                $table->index('ContactId');
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
        //
    }
}
