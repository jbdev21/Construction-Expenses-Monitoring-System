<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AccomplishmentAchievement;
use Illuminate\Http\Request;

class AccomplishmentAchievementController extends Controller
{

    function index(Request $request){
        $this->validate($request, [
            'project_id' => ['required', 'exists:projects,id'],
            'accomplishment_item_id' => ['required', 'exists:accomplishment_items,id'],
            'monthly_achievement_id' => ['required', 'exists:monthly_achievements,id']
        ]);

        $achievement = AccomplishmentAchievement::firstOrCreate([
                'project_id' =>  $request->project_id,
                'accomplishment_item_id' =>  $request->accomplishment_item_id,
                'monthly_achievement_id' =>  $request->monthly_achievement_id,
            ]
        );

        return $this->responseJsonOK('Achievement saved!', [
            'id' => $achievement->id,
            'achievement' => $achievement->achievement
        ]);
    }
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'project_id' => ['required', 'exists:projects,id'],
            'accomplishment_item_id' => ['required', 'exists:accomplishment_items,id'],
            'monthly_achievement_id' => ['required', 'exists:monthly_achievements,id'],
            'achievement' => ['required']
        ]);

        $achievement = AccomplishmentAchievement::firstOrCreate([
                'project_id' =>  $request->project_id,
                'accomplishment_item_id' =>  $request->accomplishment_item_id,
                'monthly_achievement_id' =>  $request->monthly_achievement_id,
            ]
        );

        $achievement->update(['achievement' => $request->achievement]);     

        return $this->responseJsonOK('Achievement saved!', [
            'id' => $achievement->id,
            'achievement' => $achievement->achievement
        ]);
    }
   
}
