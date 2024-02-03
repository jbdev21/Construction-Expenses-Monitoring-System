<?php

namespace App\Http\Controllers;

use App\Models\CashAdvance;
use App\Models\Personnel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashAdvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = CashAdvance::query();

        if($request->q){
            $query->whereHas("personnel", function($q) use ($request) {
                $q->where("name", "LIKE", "%" . $request->q . "%");
            });
        }

        if($request->date_from && !$request->date_to){
            $start = Carbon::parse($request->date_from);
            $end = Carbon::parse($request->date_to);
            $query->whereBetween('created_at', [$start->format('Y-m-d'), now()->addDay(1)])->get();
        }

        if($request->date_from && $request->date_to){
            $start = Carbon::parse($request->date_from);
            $end = Carbon::parse($request->date_to);
            $query->whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])->get();
        }

        $cashAdvances = $query->with('personnel')
                    ->latest()
                    ->paginate(25);

        $personnels = Personnel::all();
        return view("cash-advance.index", compact("cashAdvances", "personnels"));
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
        CashAdvance::create([
            'personnel_id' => $request->personnel_id,
            'amount' => $request->amount,
            'effectivity_date' => $request->effectivity_date
        ]);
        flash()->success("Cash advance created successfully");
        return redirect()->route("cash-advance.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CashAdvance  $cashAdvance
     * @return \Illuminate\Http\Response
     */
    public function show(CashAdvance $cashAdvance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CashAdvance  $cashAdvance
     * @return \Illuminate\Http\Response
     */
    public function edit(CashAdvance $cashAdvance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CashAdvance  $cashAdvance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CashAdvance $cashAdvance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CashAdvance  $cashAdvance
     * @return \Illuminate\Http\Response
     */
    public function destroy(CashAdvance $cashAdvance)
    {
        $cashAdvance->delete();
        flash()->success("Cash advance deleted successfully");
        return back();
    }
}
