<?php

namespace App\Observers;

use App\Models\AccomplishmentItem;

class AccomplishmentItemObserver
{
    /**
     * Handle the AccomplishmentItem "created" event.
     *
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return void
     */
    public function created(AccomplishmentItem $accomplishmentItem)
    {
        // $accomplishmentItem->total_contract_cost = $accomplishmentItem->quantity * $accomplishmentItem->unit_cost;
        // $accomplishmentItem->save();
    }

    /**
     * Handle the AccomplishmentItem "updated" event.
     *
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return void
     */
    public function updated(AccomplishmentItem $accomplishmentItem)
    {
        // $accomplishmentItem->total_contract_cost = $accomplishmentItem->quantity * $accomplishmentItem->unit_cost;
        // $accomplishmentItem->save();
    }

    /**
     * Handle the AccomplishmentItem "deleted" event.
     *
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return void
     */
    public function deleted(AccomplishmentItem $accomplishmentItem)
    {
        // $childs = AccomplishmentItem::where("parent_id", $accomplishmentItem->id)->get();
        // foreach($childs as $child){
            
        // }
        // $accomplishmentItem->accomplishmentAchievements()->each(function($q){
        //     $q->delete();
        // });
    }

    /**
     * Handle the AccomplishmentItem "restored" event.
     *
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return void
     */
    public function restored(AccomplishmentItem $accomplishmentItem)
    {
        //
    }

    /**
     * Handle the AccomplishmentItem "force deleted" event.
     *
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return void
     */
    public function forceDeleted(AccomplishmentItem $accomplishmentItem)
    {
        //
    }
}
