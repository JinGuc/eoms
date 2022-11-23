<?php

namespace App\Models;


class SnmpHost extends Model
{
    protected $guarded  = [];

    protected $appends = ['last_server_info'];

    protected $table = 'snmp_host';

    public function role()
    {
        return $this->hasMany(SnmpHostRole::class, 'hostId', 'id');
    }

    public function NotificationSettingId()
    {
        return $this->hasMany(HostNotificationSetting::class, 'hostId', 'id');
    }

    public function notification()
    {
        return $this->belongsTo(NotificationSetting::class, 'notificationId', 'id');
    }

    public function getLastServerInfoAttribute()
    {
        return ServerInfo::where('hostId', $this->attributes['id'])->latest()->first();
    }
}
