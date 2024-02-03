<?php

namespace App\Http\Controllers;

use App\Models\ProjectActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectActivityController extends Controller
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
        $activity = new ProjectActivity;
        $activity->description = $request->description;
        $activity->started = Carbon::parse($request->started);
        $activity->ended = Carbon::parse($request->ended);
        $activity->project_id = $request->project_id;
        $activity->user_id = $request->user()->id;
        $activity->save();

        //redirect
        flash()->success('Project activity created successfully');

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectActivity  $projectActivity
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectActivity $projectActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectActivity  $projectActivity
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectActivity $projectActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectActivity  $projectActivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectActivity $projectActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectActivity  $projectActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectActivity $projectActivity)
    {
        //redirect
        flash()->success('Project activity deleted successfully');
        $projectActivity->delete();
        return back();
    }
}
