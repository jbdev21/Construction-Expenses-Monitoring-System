<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Material;
use App\Models\ProjectMaterial;
use Illuminate\Http\Request;

class ProjectMaterialController extends Controller
{
    function store(Request $request){
        $project = Project::find($request->project_id);
        $material = Material::find($request->material_id);

        ProjectMaterial::create([
            'project_id' => $request->project_id,
            'material_id' => $request->material_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total_price' => $request->quantity * $request->price,
        ]);

        flash()->success('Material added successfully');
        return back();
    }

    function destroy($id){
        ProjectMaterial::find($id)->delete();
        flash()->success('Material deleted successfully');
        return back();
    }
}
