<?php

namespace App\Http\Controllers;

use App\Models\CashOnHand;
use App\Models\Ledger;
use App\Models\Personnel;
use Illuminate\Http\Request;

class CashInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Ledger::where('type', ['credit', 'cash-return'])->orderBy('effectivity_date', 'DESC')->latest();

        if($request->q) {
            $query->where('description', 'LIKE', '%' . $request->q . '%');
        }

        $ledgers = $query->paginate(25);

        return view('cash-in.index', compact('ledgers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $personnels = Personnel::all();
        return view('cash-in.create', compact('personnels'));
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
            'amount'            => ['required'],
            'personnel_id'      => ['nullable', 'exists:personnels,id'],
            'amount'            => ['required'],
            'effectivity_date'  => ['required'],
        ]);

        $ledger                     = new Ledger;
        $ledger->personnel_id       = $request->personnel_id;
        $ledger->description        = $request->description;
        $ledger->amount             = $request->amount;
        $ledger->type               = 'credit'; // as default in the DB
        $ledger->effectivity_date   = $request->effectivity_date;
        $ledger->save();

        if($request->personnel_id){
            $personnel = Personnel::find($request->personnel_id);
            $personnel->decrement("current_cash_advance", $request->amount);
        }

         //if petty cash effectivity_date provided then update ledger rehistory
            $effectivityDate = $ledger->effectivity_date;
            $diff = $effectivityDate->diffInDays(now());

            if ($diff > 0) {
                $this->updateHistory($ledger);
            }

        flash()->success("Cash in created successfully");
        return redirect()->route("cash-in.index");
    }

    function updateHistory($ledger){
        // get the last prev cash on hand that will be the basis (ex effectivty_date = 10 then the prev last ledger is from 9)
        $prev_cash_on_hand = Ledger::whereDate('effectivity_date', '<', $ledger->effectivity_date->format("Y-m-d"))
                            ->orderBy('id', 'DESC')
                            ->latest()
                            ->first();
        
        $cashOnhand = CashOnHand::get()->first();

        $cashOnhand->update(['amount' => $prev_cash_on_hand->onhand_amount]);

        // get all ledger starting from effectivity date given
        $ledgers = Ledger::whereDate('effectivity_date', '>=', $ledger->effectivity_date)
            ->orderBy('effectivity_date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

    // do the redistory untill the end of ledger
        foreach ($ledgers as $ledger) {

            if ($ledger->type == "credit") {
                $cashOnhand->increment('amount', $ledger->amount);
            } else {
                $cashOnhand->decrement('amount', $ledger->amount);
            }

            $ledger->update(['onhand_amount' => CashOnHand::first()->amount]);
        }   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return \Illuminate\Http\Response
     */
    public function show(Ledger $ledger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return \Illuminate\Http\Response
     */
    public function edit(Ledger $ledger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ledger  $ledger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ledger $ledger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ledger = Ledger::find($id);
        $ledger->delete();
        flash()->success("Cash in deleted successfully");
        return back();
    }
}
