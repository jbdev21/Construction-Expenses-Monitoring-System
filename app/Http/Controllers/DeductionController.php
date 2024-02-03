<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $deductions = Deduction::query()
                    ->when($request->q, function(Builder $builder) use ($request) {
                        $builder->where('name', 'LIKE', '%' . $request->q . '%');
                    })
                    ->paginate(25);

        return view('deduction.index', compact('deductions'));
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
            'name' => ['required'],
            'type' => ['required', 'string'], 
            'figure' => ['required'], 
        ]);

        Deduction::create([
            'name' => $request->name,
            'type' => $request->type,
            'figure' => $request->figure,
        ]);

        flash()->success('Deduction added successfully!');
        return redirect()->route('deduction.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function show(Deduction $deduction)
    {
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function edit(Deduction $deduction)
    {
        return view('deduction.edit', compact('deduction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deduction $deduction)
    {
        $this->validate($request, [
            'name' => ['required'],
            'type' => ['required', 'string'], 
            'figure' => ['required'], 
        ]);

        $deduction->update([
            'name' => $request->name,
            'type' => $request->type,
            'figure' => $request->figure,
        ]);

        flash()->success('Deduction updated successfully!');
        return redirect()->route('deduction.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deduction $deduction)
    {
        $deduction->delete();
        flash()->success('Deduction deleted successfully!');
        return back();
    }
}
