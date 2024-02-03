<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Ledger;
use App\Models\PettyCash;
use App\Models\CashOnHand;

class PettyCashObserver
{
    /**
     * Handle the PettyCash "created" event.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return void
     */
    public function created(PettyCash $pettyCash)
    {
    
    }

    /**
     * Handle the PettyCash "updated" event.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return void
     */
    public function updated(PettyCash $pettyCash)
    {
        //
    }

    /**
     * Handle the PettyCash "deleted" event.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return void
     */
    public function deleted(PettyCash $pettyCash)
    {
        //
    }

    /**
     * Handle the PettyCash "restored" event.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return void
     */
    public function restored(PettyCash $pettyCash)
    {
        $pettyCash->ledgers()->delete();
    }

    /**
     * Handle the PettyCash "force deleted" event.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return void
     */
    public function forceDeleted(PettyCash $pettyCash)
    {
        $pettyCash->ledgers()->delete();
    }
}
