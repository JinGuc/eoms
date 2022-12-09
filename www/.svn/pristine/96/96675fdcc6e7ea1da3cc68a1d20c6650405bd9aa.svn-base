<?php

namespace App\Models;

class ServerInfo extends Model
{
    protected $guarded  = [];

    protected $table = 'server_info';

    public function snmpHost()
    {
        return $this->belongsTo(SnmpHost::class, 'hostId', 'id');
    }
}
