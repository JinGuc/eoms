<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('notification_log')) {
            Schema::create('notification_log', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('hostId')->comment("服务器id");
                $table->bigInteger('notificationId')->comment("告警设置id");
                $table->tinyInteger('notificationType')->comment("告警类型 1cpu使用率 2内存使用率 3系统负载1m 4系统负载5m 5系统负载15m 6硬盘使用率");
                $table->string('ContactId',1000)->comment("通讯录id 多个联系人使用英文逗号分割");
                $table->longText('info')->comment("告警内容");
                $table->tinyInteger('status')->comment("状态 1未处理 2已处理")->default(1);
                $table->index('hostId');
                $table->index('notificationId');
                $table->index('status');
                $table->index('created_at');
                $table->index('updated_at');
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
