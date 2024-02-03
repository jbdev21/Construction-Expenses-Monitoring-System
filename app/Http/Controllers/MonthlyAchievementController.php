<?php

namespace App\Http\Controllers;

use App\Models\MonthlyAchievement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MonthlyAchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'project_id' => ['required'],
            'weight' => ['required'],
            'month' => ['required'],
            'label' => ['required'],
        ]);

        $month = Carbon::parse($request->month);

        MonthlyAchievement::create([
            'project_id' => $request->project_id,
            'label' => $request->label,
            'weight' => $request->weight,
            'month' => $month->format('m'),
            'year' => $month->format("Y"),
            'complete_month_date' => $month->format("Y-m-01")
        ]);

        flash()->success('Monthly Item added!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MonthlyAchievement  $monthlyAchievement
     * @return \Illuminate\Http\Response
     */
    public function show(MonthlyAchievement $monthlyAchievement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MonthlyAchievement  $monthlyAchievement
     * @return \Illuminate\Http\Response
     */
    public function edit(MonthlyAchievement $monthlyAchievement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MonthlyAchievement  $monthlyAchievement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MonthlyAchievement $monthlyAchievement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MonthlyAchievement  $monthlyAchievement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MonthlyAchievement $monthlyAchievement)
    {
        $monthlyAchievement->delete();

        if($request->ajax()){
            return response()
                    ->json([
                        'status' => 200,
                        'message' => 'Deleted!'
                    ], 200);
        }

        flash()->success("Monthly Achievement Deleted!");
        return back();
    }
}
