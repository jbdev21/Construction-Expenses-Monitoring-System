<?php

namespace App\Http\Controllers;

use App\Models\SubContract;
use Illuminate\Http\Request;

class SubContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
            'name' => ['required'],
            'contract_amount' => ['required'],
        ]);

        SubContract::create([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'contract_amount' => $request->contract_amount,
            'description' => $request->description,
        ]);

        flash()->success('Pakyaw/Sub Contract has been created successfully');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubContract  $subContract
     * @return \Illuminate\Http\Response
     */
    public function show(SubContract $subContract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubContract  $subContract
     * @return \Illuminate\Http\Response
     */
    public function edit(SubContract $subContract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubContract  $subContract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubContract $subContract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubContract  $subContract
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubContract $subContract)
    {
        $subContract->delete();
        flash()->success("Pakyaw/Sub Contract has been deleted successfully");
        return back();
    }
}
