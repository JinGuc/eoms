<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ServerInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('server_info')) {
            Schema::create('server_info', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('hostId')->comment("服务器id");
                $table->string('system_version',200)->comment("系统版本");
                $table->decimal('cpu_use',10,2)->comment("cpu使用率");
                $table->string('memory_use',255)->comment("已使用内存量,单位（M）");
                $table->string('memory_total',255)->comment("总内存量,单位（M）");
                $table->decimal('mem_used_percent',10,2)->comment("内存使用率");
                $table->integer('cpu_core_num')->comment("cpu核心数");
                $table->string('cpu_info',150)->comment("cpu信息");
                $table->string('temperature',100)->comment("cpu温度")->default("");
                $table->text('storage_info')->comment("磁盘信息，包含路径，已用量，可用量，使用率，单位（KB）");
                $table->text('disk_info')->comment("磁盘列表，单位（KB）");
                $table->text('disk_io')->comment("磁盘io信息，单位（KB）");
                $table->decimal('cpu_load1',10,2)->comment("一分钟负载");
                $table->decimal('cpu_load5',10,2)->comment("五分钟负载");
                $table->decimal('cpu_load15',10,2)->comment("十五分钟负载");
                $table->string('tcp_connect_info',255)->comment("tcp连接数");
                $table->longText('tcp_port')->comment("已开放tcp端口");
                $table->longText('udp_port')->comment("已开放udp端口");
                $table->string('net_speed',255)->comment("当前网络速度");
                $table->longText('hrswrunname')->comment("运行的进程名称");
                $table->datetime('system_time')->comment("系统时间");
                $table->string('system_runtime',500)->comment("系统以运行时间");
                $table->integer('today_login_error_totalCount')->comment("今日用户登陆失败次数")->default(0);
                $table->integer('today_login_success_totalCount')->comment("今日用户登陆成功次数")->default(0);
                $table->index('hostId');
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
