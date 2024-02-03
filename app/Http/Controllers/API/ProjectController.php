<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Project\AccomplishmentResource;
use App\Models\AccomplishmentItem;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    function show(Request $request, $id){
        $project = Project::findOrFail($id);
        if($request->accomplishment){
            $project->load('accomplishmentItems');
        }
        
        if($request->monthly){
            $project->load('monthlyAchievements');
        }

        return $project;
    }
    
    function projectAchievement(Request $request, $id){
        $project = Project::findOrFail($id)
                    ->load(['accomplishmentItems', 'monthlyAchievements']);

        return [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'contract_amount' => $project->contract_amount,
                'total_weight' => $project->accomplishmentItems()->sum('weight'),
                'total_contract_cost' => $project->accomplishmentItems()->sum('total_contract_cost'),
                'total_weight_accomplished' => $project->accomplishmentItems()->sum('weight_accomplished'),
                'total_cost_billing' => $project->accomplishmentItems()->sum('cost_billing'),
                'total_revised_contract_cost' => $project->accomplishmentItems()->sum('revised_contract_cost')
            ],
            'accomplishment_items' => AccomplishmentResource::collection($project->accomplishmentItems()->with('project')->get()),
            'monthly_achievements' => $project->monthlyAchievements
        ];
    }

    function updateWeightProgress(Request $request, Project $project){
        $project->load('accomplishmentItems');
        $project->update(['weight_progress' => round($project->accomplishmentItems()->sum('weight_accomplished'), 2)]);
        return response()
            ->json([
                'status' => 200,
                'message' => 'Project progress updated!',
                'data' => [
                    'weigth' => $request->weight
                ]
            ], 200);
    }



    function selectData(Request $request){
        return Project::search($request->key)
            ->take(5)
            ->get()
            ->map(function($query){
                return [
                    'label' => $query->name,
                    'code' => $query->id,
                    'class' => Project::class
                ];
            });
    }


    function select2(Request $request){
        return Project::where('name', 'LIKE', '%' . $request->searchTerm . '%')
                ->take(8)
                ->get()
                ->map(function($q){
                    return [
                        'id' => $q->id,
                        'text' => $q->name,
                    ];
                });
    }
}
