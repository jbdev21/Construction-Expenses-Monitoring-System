<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Ledger::query()
                    ->whereIn("type", ['credit', 'debit'])
                    ->orderBy('effectivity_date', 'DESC')
                    ->orderBy("id", 'DESC');

        if($request->q) {
            $query->where('description', 'LIKE', '%' . $request->q . '%');
        }

        if($request->type) {
            $query->where('type', $request->type);
        }

         if($request->date_from && !$request->date_to){
            $start = Carbon::parse($request->date_from);
            $end = Carbon::parse($request->date_to);
            $query->whereBetween('effectivity_date', [$start->format('Y-m-d'), now()->addDay(1)])->get();
        }

        if($request->date_from && $request->date_to){
            $start = Carbon::parse($request->date_from);
            $end = Carbon::parse($request->date_to);
            $query->whereBetween('effectivity_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])->get();
        }

        $ledgers = $query->paginate(40);

        return view('ledger.index', compact('ledgers'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
