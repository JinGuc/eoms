<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('snmp:sysInfo')->everyMinute()->withoutOverlapping(); //每分钟执行一次且在上次运行完后
        $schedule->command('snmp:sysInfo')->everyMinute()->runInBackground(); //每分钟执行一次
        $schedule->command('snmp:roleInfo')->everyMinute()->runInBackground()->withoutOverlapping(); //每分钟执行一次
        $schedule->command('snmp:hostInfo')->everyMinute()->runInBackground()->withoutOverlapping(); //每分钟执行一次
        //接口状态监控
        $schedule->command('snmp:UrlStatusInfo')->everyMinute()->runInBackground()->withoutOverlapping(); //每分钟执行一次
        //删除30天前的服务器采集数据信息
        $schedule->command('snmp:deleteServerInfo')->daily()->runInBackground()->withoutOverlapping(); //每天凌晨零点运行任务
        //告警任务记录
        //$schedule->command('notice:HostWarning')->everyMinute()->runInBackground()->withoutOverlapping(); //每分钟执行一次
        //告警日志记录
        $schedule->command('notice:InsertWarningLog')->everyMinute()->runInBackground()->withoutOverlapping(); //每分钟执行一次
        //告警推送负责人记录
        $schedule->command('notice:SendWarningPlan')->everyMinute()->runInBackground()->withoutOverlapping(); //每分钟执行一次
        // $schedule->command('websockets:serve --host='.config('websockets.dashboard.host').' --port='.config('websockets.dashboard.port'))->everyMinute()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
