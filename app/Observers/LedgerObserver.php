<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Ledger;
use App\Models\CashOnHand;

class LedgerObserver
{
    /**
     * Handle the Ledger "created" event.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return void
     */
    public function created(Ledger $ledger)
    {
        $cashOnhand = CashOnHand::first();

        // is to update the amount column in petty cash in each ledger added
        if($ledger->pettyCash){
            $ledger->pettyCash->increment('amount', $ledger->amount);
        }

        if($ledger->type == "credit"){
            $cashOnhand->increment('amount', $ledger->amount);
            $ledger->update(['onhand_amount' => CashOnHand::first()->amount]);
        }elseif($ledger->type == "debit"){
            $cashOnhand->decrement('amount', $ledger->amount);
            $ledger->update(['onhand_amount' => CashOnHand::first()->amount]);
        }

    }

    /**
     * Handle the Ledger "updated" event.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return void
     */
    public function updated(Ledger $ledger)
    {
        //
    }

    /**
     * Handle the Ledger "deleted" event.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return void
     */
    public function deleted(Ledger $ledger)
    {
        $cashOnhand = CashOnHand::first();

        if($ledger->type == "credit"){
            $cashOnhand->decrement('amount', $ledger->amount);
        }elseif($ledger->type == "debit"){
            $cashOnhand->increment('amount', $ledger->amount);
        }

        $ledger->update(['onhand_amount' => CashOnHand::first()->amount]);

    }

    /**
     * Handle the Ledger "restored" event.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return void
     */
    public function restored(Ledger $ledger)
    {
        //
    }

    /**
     * Handle the Ledger "force deleted" event.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return void
     */
    public function forceDeleted(Ledger $ledger)
    {
        //
    }
}
