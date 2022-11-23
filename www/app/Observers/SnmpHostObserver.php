<?php

namespace App\Observers;

use App\Models\SnmpHost ;
use Illuminate\Support\Facades\Log;

class SnmpHostObserver
{
    /**
     * Handle the SnmpHost "created" event.
     *
     * @param  \App\Models\SnmpHost  $SnmpHost
     * @return void
     */
    public function created(SnmpHost $SnmpHost)
    {
        //
    }

    /**
     * Handle the SnmpHost "updated" event.
     *
     * @param  \App\Models\SnmpHost  $SnmpHost
     * @return void
     */
    public function updated(SnmpHost $SnmpHost)
    {
        //
    }

    /**
     * Handle the SnmpHost "deleted" event.
     *
     * @param  \App\Models\SnmpHost  $SnmpHost
     * @return void
     */
    public function deleted(SnmpHost $SnmpHost)
    {
        //
    }

    /**
     * Handle the SnmpHost "restored" event.
     *
     * @param  \App\Models\SnmpHost  $SnmpHost
     * @return void
     */
    public function restored(SnmpHost $SnmpHost)
    {
        //
    }

    /**
     * Handle the SnmpHost "force deleted" event.
     *
     * @param  \App\Models\SnmpHost  $SnmpHost
     * @return void
     */
    public function forceDeleted(SnmpHost $SnmpHost)
    {
        //
    }
}
