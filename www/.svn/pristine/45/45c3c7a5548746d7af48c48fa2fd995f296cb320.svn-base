<?php

namespace App\Models;

class HostNotificationSetting extends Model
{
    protected $guarded  = [];

    protected $table = 'host_notification_setting';

    public function host()
    {
        return $this->belongsTo(SnmpHost::class, 'hostId', 'id');
    }
    public function notification()
    {
        return $this->belongsTo(NotificationSetting::class, 'notificationId', 'id');
    }

}
