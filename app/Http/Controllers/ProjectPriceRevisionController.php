<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectPriceRevision;
use Illuminate\Http\Request;

class ProjectPriceRevisionController extends Controller
{
    function store(Request $request){
        $project = Project::find($request->id);
        ProjectPriceRevision::create([
            'project_id' => $request->project_id,
            'amount' => $request->amount,
            'type' => $request->type
        ]);

        flash()->success('Project price revision has been added');
        return redirect()->back();
    }

    function destroy($id){
        ProjectPriceRevision::find($id)->delete();
        flash()->success('Project price revision has been deleted!');
        return back();
    }
}
