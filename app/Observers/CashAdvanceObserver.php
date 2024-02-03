<?php

namespace App\Observers;

use App\Models\CashAdvance;
use App\Models\Ledger;
use App\Models\Personnel;

class CashAdvanceObserver
{
    /**
     * Handle the CashAdvance "created" event.
     *
     * @param  \App\Models\CashAdvance  $cashAdvance
     * @return void
     */
    public function created(CashAdvance $cashAdvance)
    {
        $personnel = Personnel::find($cashAdvance->personnel_id);

        if($personnel->current_cash_advance == null){
            $personnel->current_cash_advance = $cashAdvance->amount;
            $personnel->save();
        }else{
            $personnel->increment('current_cash_advance', $cashAdvance->amount);
        }

    }

    /**
     * Handle the CashAdvance "updated" event.
     *
     * @param  \App\Models\CashAdvance  $cashAdvance
     * @return void
     */
    public function updated(CashAdvance $cashAdvance)
    {
        
    }

    /**
     * Handle the CashAdvance "deleted" event.
     *
     * @param  \App\Models\CashAdvance  $cashAdvance
     * @return void
     */
    public function deleted(CashAdvance $cashAdvance)
    {
        $personnel = Personnel::find($cashAdvance->personnel_id);

        if($personnel->current_cash_advance = null){
            $personnel->current_cash_advance = $cashAdvance->amount;
            $personnel->save();
        }

        $personnel->decrement('current_cash_advance', $cashAdvance->amount);
    }

    /**
     * Handle the CashAdvance "restored" event.
     *
     * @param  \App\Models\CashAdvance  $cashAdvance
     * @return void
     */
    public function restored(CashAdvance $cashAdvance)
    {
        //
    }

    /**
     * Handle the CashAdvance "force deleted" event.
     *
     * @param  \App\Models\CashAdvance  $cashAdvance
     * @return void
     */
    public function forceDeleted(CashAdvance $cashAdvance)
    {
        //
    }
}
