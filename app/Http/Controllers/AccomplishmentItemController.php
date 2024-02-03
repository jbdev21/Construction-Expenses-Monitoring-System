<?php

namespace App\Http\Controllers;

use App\Models\AccomplishmentItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccomplishmentItemController extends Controller
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
        $this->validate($request, [
            'project_id' => ['required', 'exists:projects,id'],
            'parent_id' => ['nullable', 'exists:accomplishment_items,id'],
            'type' => ['required', Rule::in(['item', 'group'])],
            'name' => ['required']
        ]);

        AccomplishmentItem::create([
            'project_id' => $request->project_id,
            'parent_id' => $request->parent_id,
            'item_number' => $request->item_number,
            'name' => $request->name, 
            'unit' => $request->unit,
            'type' => $request->type,
            'weight' => $request->weight,
            'quantity' => $request->quantity,
            'unit_cost' => $request->unit_cost,
            'total_contract_cost' => $request->quantity * $request->unit_cost, 
            
            // revised
            'revised_quantity' => $request->revised_quantity ?? $request->quantity,
            'revised_unit_cost' => $request->revised_unit_cost ?? $request->unit_cost,
            'revised_contract_cost' => ($request->revised_quantity ?? $request->quantity) * ($request->revised_unit_cost ?? $request->unit_cost),
            
        ]);

        if($request->ajax()){
            
        }
        flash()->success('Accomplistment Item added!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return \Illuminate\Http\Response
     */
    public function show(AccomplishmentItem $accomplishmentItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return \Illuminate\Http\Response
     */
    public function edit(AccomplishmentItem $accomplishmentItem)
    {
        return view('project.show.accomplishment', compact('accomplishmentItem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccomplishmentItem $accomplishmentItem)
    {
        $accomplishmentItem->update($request->except("_token"));

        return response()->json([
            'status' => 200,
            'message' => 'Updated',
            'data' => $accomplishmentItem
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return \Illuminate\Http\Response
     */
    public function updateWeightAccomplish(Request $request, AccomplishmentItem $accomplishmentItem)
    {
        $accomplishmentItem->update(['weight_accomplished' => $request->weight_accomplished]);
    
        if($request->ajax()){
            return response()->json([
                'status' => 200,
                'message' => 'updated!'
            ], 200);
        }
        
        return back();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return \Illuminate\Http\Response
     */
    public function updateCostBilling(Request $request, AccomplishmentItem $accomplishmentItem)
    {
        $accomplishmentItem->update(['cost_billing' => $request->cost_billing]);
    
        if($request->ajax()){
            return response()->json([
                'status' => 200,
                'message' => 'updated!'
            ], 200);
        }
        
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, AccomplishmentItem $accomplishmentItem)
    {
        $childs = AccomplishmentItem::where("parent_id", $accomplishmentItem->id)->get();
        foreach($childs as $child){
            $child->accomplishmentAchievements()->delete();
            $child->delete();
        }
        $accomplishmentItem->accomplishmentAchievements()->delete();
        $accomplishmentItem->delete();
    
        
        if($request->ajax()){
            return response()->json([
                'status' => 200,
                'message' => 'deleted!'
            ], 200);
        }
    }
}
