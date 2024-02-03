<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
    public function index(Request $request)
    {
        return AccomplishmentItem::query()
                ->when($request->project, function($q) use ($request){
                    $q->where("project_id", $request->project);
                })
                ->when($request->type, fn($q) => $q->whereType($request->type))
                ->get();
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
            'name' => $request->name, 
            'type' => $request->type,
            'unit' => $request->unit,
            'item_number' => $request->item_number,
            'weight' => $request->weight,
            'quantity' => $request->quantity,
            'revised_contract_quantity' => $request->revised_contract_quantity,
            'unit_cost' => $request->unit_cost,
            'total_contract_cost' => $request->quantity * $request->unit_cost,
        ]);

        
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return \Illuminate\Http\Response
     */
    public function edit(AccomplishmentItem $accomplishmentItem)
    {
        //
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
        return $request->all();
        // $accomplishmentItem->update($request->except);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccomplishmentItem  $accomplishmentItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccomplishmentItem $accomplishmentItem)
    {
        //
    }
}
