<?php

namespace App\Observers;

use App\Models\MonthlyAchievement;

class MonthlyAchievementObserver
{
    /**
     * Handle the MonthlyAchievement "created" event.
     *
     * @param  \App\Models\MonthlyAchievement  $monthlyAchievement
     * @return void
     */
    public function created(MonthlyAchievement $monthlyAchievement)
    {
        // $monthlyAchievement->complete_month_date = $monthlyAchievement->year . "-" . $monthlyAchievement->month . "-01";
        // $monthlyAchievement->save();
    }

    /**
     * Handle the MonthlyAchievement "updated" event.
     *
     * @param  \App\Models\MonthlyAchievement  $monthlyAchievement
     * @return void
     */
    public function updated(MonthlyAchievement $monthlyAchievement)
    {
        $monthlyAchievement->complete_month_date = $monthlyAchievement->year . "-" . $monthlyAchievement->month . "-01";
        $monthlyAchievement->save();
    }

    /**
     * Handle the MonthlyAchievement "deleted" event.
     *
     * @param  \App\Models\MonthlyAchievement  $monthlyAchievement
     * @return void
     */
    public function deleted(MonthlyAchievement $monthlyAchievement)
    {
        $monthlyAchievement->accomplishmentAchievements()->delete();
    }

    /**
     * Handle the MonthlyAchievement "restored" event.
     *
     * @param  \App\Models\MonthlyAchievement  $monthlyAchievement
     * @return void
     */
    public function restored(MonthlyAchievement $monthlyAchievement)
    {
        //
    }

    /**
     * Handle the MonthlyAchievement "force deleted" event.
     *
     * @param  \App\Models\MonthlyAchievement  $monthlyAchievement
     * @return void
     */
    public function forceDeleted(MonthlyAchievement $monthlyAchievement)
    {
        //
    }
}
