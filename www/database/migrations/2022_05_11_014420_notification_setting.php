<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('notification_setting')) {
            Schema::create('notification_setting', function (Blueprint $table) {
                $table->id();
                $table->string('title',100)->comment("告警名称");
                $table->tinyInteger('status')->comment("状态 1启用 2禁用")->default('1');
                $table->tinyInteger('type')->comment("告警类型 1cpu使用率 2内存使用率 3系统负载1m 4系统负载5m 5系统负载15m 6硬盘使用率");
                $table->tinyInteger('sendType')->comment("告警方式 1电话 2短信 3邮件 4电话+短信 5电话+邮件 6短信+邮件 7电话+短信+邮件");
                $table->integer('cycle')->comment("统计周期 单位分钟");
                $table->integer('continueCycle')->comment("持续周期");
                $table->tinyInteger('countType')->comment("统计类型 1最大值 2最小值 3平均值");
                $table->tinyInteger('operator')->comment("运算符 1大于 2大于等于 3等于 4小于 5小于等于");
                $table->integer('silenceCycle')->comment("沉默周期 单位分钟");
                $table->string('value')->comment("阈值");
                $table->time('start')->comment("有效开始时间")->default('00:00:00');
                $table->time('end')->comment("有效截至时间")->default('23:59:59');
                $table->string('ContactId',1000)->comment("通讯录id 多个联系人使用英文逗号分割");
                $table->string('content',1000)->comment("告警内容");
                $table->index('status');
                $table->index('type');
                $table->index('sendType');
                $table->index('start');
                $table->index('end');
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
