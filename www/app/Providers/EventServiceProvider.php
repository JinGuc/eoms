<?php

namespace App\Providers;

use App\Events\Snmp\HostEvent;
use App\Events\Snmp\SysInfoEvent;
use App\Models\ServerInfo;
use App\Models\SnmpHostInfo;
use App\Models\UrlStatusInfo;
use App\Observers\ServerInfoObserver;
use App\Observers\SnmpHostInfoObserver;
use App\Observers\UrlStatusInfoObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
#use SnmpHostInfo;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        SysInfoEvent::class;
        HostEvent::class;
        ServerInfo::observe(ServerInfoObserver::class);
        SnmpHostInfo::observe(SnmpHostInfoObserver::class);
        UrlStatusInfo::observe(UrlStatusInfoObserver::class);
        
        //
    }
}
