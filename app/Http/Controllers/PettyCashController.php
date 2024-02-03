<?php

namespace App\Http\Controllers;

use App\Models\CashAdvance;
use App\Models\CashOnHand;
use App\Models\Expense;
use App\Models\Ledger;
use App\Models\Personnel;
use App\Models\PettyCash;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PettyCashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Ledger::where('type', 'debit')->has("pettyCash");

        if($request->q){
            $query->where('description', 'LIKE', '%' . $request->q . '%');
        }

        if($request->type){
            $query->where('type', Str::lower($request->type));
        }


        if($request->date_from && !$request->date_to){
            $start = Carbon::parse($request->date_from);
            $end = Carbon::parse($request->date_to);
            $query->whereBetween('created_at', [$start->format('Y-m-d'), now()->addDay(1)])->get();
        }

        if($request->date_from && $request->date_to){
            $start = Carbon::parse($request->date_from);
            $end = Carbon::parse($request->date_to);
            $query->whereBetween('created_at', [$start->format('Y-m-d'), $end->addDay(1)->format('Y-m-d')])->get();
        }

        $ledgers = $query
                    ->with("pettyCash")
                    ->orderBy('effectivity_date', 'DESC')
                    ->orderBy("created_at", 'DESC')
                    ->paginate(45);

        return view('petty_cash.index', compact('ledgers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $personnels = Personnel::all();
        return view('petty_cash.create', compact('personnels'));
    }

    public function salaryForm(Request $request){
        $personnels = Personnel::all();
        return view('petty_cash.salary', compact('personnels'));

    }

    public function salarySubmit(Request $request){
        //validate request
        $this->validate($request, [
            'effectivity_date'  => ['required', 'date'],
            'personnel_id'      => ['nullable', 'exists:personnels,id'],
            'salary'            => ['required'],
            'items'             => ['nullable', 'array'],     
            'amount'            => ['nullable', 'array'],     
        ]);

        $personnel = Personnel::find($request->personnel_id);
        $helperTotalAmount = 0;
        $helperTextExtension = "";
        $deductionText = "";

        //store petty_cash
        $petty_cash = new PettyCash;
        $petty_cash->payee = Str::upper($personnel->name);
        $petty_cash->personnel_id = $request->personnel_id;
        $petty_cash->effectivity_date = $request->effectivity_date;
        $petty_cash->save();

        if($request->items){
            foreach($request->items as $index => $item){
                $helperTotalAmount += $request->amount[$index];
            }
            $helperTextExtension = " WITH " . count($request->items) . " HELPER(s) " . toPeso($helperTotalAmount);
        }

        if($request->deduction){
            $deductionText = "(CA Deduction:" . toPeso($request->deduction) . ")";
        }
        
        $ledger                     = new Ledger;
        $ledger->petty_cash_id      = $petty_cash->id;
        $ledger->description        = "SALARY " . Str::upper($personnel->name) . ' ' . $petty_cash->effectivity_date->format('Y-m-d') . 
                                        " " . $deductionText
                                        . " " . $helperTextExtension;
        $ledger->amount             = $request->salary + $helperTotalAmount - ($request->deduction ?? 0);
        $ledger->type               = 'debit'; 
        $ledger->category           = 'labor'; 
        $ledger->effectivity_date   = $request->effectivity_date;
        $ledger->save();

        if($request->deduction){
            $ledger                     = new Ledger;
            $ledger->personnel_id       = $request->personnel_id;
            $ledger->description        = "CASH RETURN FROM " . Str::upper($personnel->name) . " " . $petty_cash->effectivity_date->format('Y-m-d');// payment
            $ledger->amount             = $request->deduction;
            $ledger->type               = 'cash-return'; // as default in the DB
            $ledger->effectivity_date   = $request->effectivity_date;
            $ledger->save();
    
            if($request->personnel_id){
                $personnel->decrement("current_cash_advance", $request->deduction);
            }
        }

        //if petty cash effectivity_date provided then update ledger rehistory
            $effectivityDate = $petty_cash->effectivity_date;
            $diff = $effectivityDate->diffInDays(now());

            if ($diff > 0) {
                $this->updateHistory($petty_cash);
            }

        //redirect
        flash()->success('Petty Cash created successfully');
        return redirect(route('petty-cash.print', $petty_cash->id) . "?redirect=" . route('petty-cash.index') . "&eloremedotgs=1");
    }


    function updateHistory($pettyCash){
        // get the last prev cash on hand that will be the basis (ex effectivty_date = 10 then the prev last ledger is from 9)
        $prev_cash_on_hand = Ledger::whereDate('effectivity_date', '<', $pettyCash->effectivity_date->format("Y-m-d"))
                            ->orderBy('id', 'DESC')
                            ->latest()
                            ->first();
        
        $cashOnhand = CashOnHand::get()->first();

        $cashOnhand->update(['amount' => ($prev_cash_on_hand->onhand_amount ?? 0)]);

        // get all ledger starting from effectivity date given
        $ledgers = Ledger::whereDate('effectivity_date', '>=', $pettyCash->effectivity_date)
            ->orderBy('effectivity_date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

    // do the redistory untill the end of ledger
        foreach ($ledgers as $ledger) {

            if($ledger->type == "credit"){
                $cashOnhand->increment('amount', $ledger->amount);
            }elseif($ledger->type == "debit"){
                $cashOnhand->decrement('amount', $ledger->amount);
            }

            $ledger->update(['onhand_amount' => CashOnHand::first()->amount]);
        }   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate request
        $this->validate($request, [
            'payee'             => ['required'],
            'effectivity_date'  => ['required', 'date'],
            'personnel_id'      => ['nullable', 'exists:personnels,id'],
            'items'             => ['required', 'array'],
            'amount'            => ['required', 'array']
        ]);

        //store petty_cash
        $petty_cash = new PettyCash;
        $petty_cash->payee = $request->payee;
        $petty_cash->personnel_id = $request->personnel_id;
        $petty_cash->effectivity_date = $request->effectivity_date;
        $petty_cash->project_id = $request->project_id;
        $petty_cash->save();

        if(count($request->items)){
            $count = 0;
            foreach ($request->items as $item) {
                $ledger                     = new Ledger;
                $ledger->personnel_id       = $request->personnel_id;
                $ledger->project_id         = $request->project_id;
                $ledger->petty_cash_id      = $petty_cash->id;
                $ledger->description        = $item;
                $ledger->amount             = $request->amount[$count];
                $ledger->type               = 'debit'; // as default in the DB
                $ledger->category           = $request->category[$count]; // as default in the DB
                $ledger->effectivity_date   = $request->effectivity_date;
                $ledger->save();
                

                if($request->project_id){
                    Expense::create([
                        'items' => $item,
                        'amount' => $request->amount[$count],
                        'unit_quantity' => 1,
                        'unit_price' => $request->amount[$count],
                        'type' => $request->category[$count],
                        'effectivity_date' => $request->effectivity_date,
                        'project_id' => $request->project_id,
                        'petty_cash_id' => $petty_cash->id,
                    ]);
                }

                $count++;
            }
        }

        if($request->personnel_id){
            CashAdvance::create([
                'personnel_id' => $request->personnel_id,
                'amount' => $request->amount[0],
                'effectivity_date' => $request->effectivity_date
            ]);
        }

        //if petty cash effectivity_date provided then update ledger rehistory
            $effectivityDate = $petty_cash->effectivity_date;
            $diff = $effectivityDate->diffInDays(now());

            if ($diff > 0) {
                $this->updateHistory($petty_cash);
            }

        //redirect
        flash()->success('Petty Cash created successfully');
        return redirect(route('petty-cash.print', $petty_cash->id) . "?redirect=" . route('petty-cash.index') . "&eloremedotgs=1");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return \Illuminate\Http\Response
     */
    public function show(PettyCash $pettyCash)
    {
        //
    }

    public function print(Request $request, $id)
    {
        $petty_cash = PettyCash::find($id);

        if($petty_cash){

        }

        return view('petty_cash.print', compact('petty_cash'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return \Illuminate\Http\Response
     */
    public function edit(PettyCash $pettyCash)
    {
        $pettyCash->load("project");
        $personnels = Personnel::all();
        
        if($pettyCash->type == "salary"){
            $salary = $pettyCash->data['salary']['amount'];
            $helpers = collect($pettyCash->data['helpers']);
            return view("salary.edit", compact('pettyCash', 'personnels', 'salary', 'helpers'));
        }

        return view("petty_cash.edit", compact('pettyCash', 'personnels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PettyCash  $pettyCash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PettyCash $pettyCash)
    {
        
        //validate request
        $this->validate($request, [
            'payee'             => ['required'],
            'effectivity_date'  => ['required', 'date'],
            'personnel_id'      => ['nullable', 'exists:personnels,id'],
            'items'             => ['required', 'array'],
            'amount'            => ['required', 'array']
        ]);

        foreach($pettyCash->ledgers as $ledger){
            $ledger->delete();
        }

        //store petty_cash
        $pettyCash->payee = $request->payee;
        $pettyCash->personnel_id = $request->personnel_id;
        $pettyCash->effectivity_date = $request->effectivity_date;

        $dirtyPersonnel = $pettyCash->isDirty('personnel_id');
        $pettyCash->amount = 0;
        $pettyCash->save();
        $pettyCash->project_id = $request->project_id;
        $pettyCash->update(['amount' => 0]);

        Expense::where("petty_cash_id", $pettyCash->id)->delete();

        if(count($request->items)){
            $count = 0;
            foreach ($request->items as $item) {
                $ledger                     = new Ledger;
                $ledger->personnel_id       = $request->personnel_id;
                $ledger->petty_cash_id      = $pettyCash->id;
                $ledger->project_id         = $request->project_id;
                $ledger->description        = $item;
                $ledger->amount             = $request->amount[$count];
                $ledger->type               = 'debit'; // as default in the DB
                $ledger->category           = $request->category[$count];; // as default in the DB
                $ledger->effectivity_date   = $request->effectivity_date;
                $ledger->save();

                if($request->project_id){
                    Expense::create([
                        'items' => $item,
                        'amount' => $request->amount[$count],
                        'unit_quantity' => 1,
                        'unit_price' => $request->amount[$count],
                        'type' => $request->category[$count],
                        'effectivity_date' => $request->effectivity_date,
                        'project_id' => $request->project_id,
                        'petty_cash_id' => $pettyCash->id,
                    ]);
                }
                
                $count++;
            }

            
        }

        if($request->personnel_id){
            if($dirtyPersonnel){  
                CashAdvance::where('personnel_id',$request->personnel_id)->first()->delete();
                CashAdvance::create([
                    'personnel_id' => $request->personnel_id,
                    'amount' => $request->amount[0],
                    'effectivity_date' => $request->effectivity_date
                ]);
            }
        }

        //redirect
        flash()->success('Petty Cash updated successfully');
        return redirect(route('petty-cash.print', $pettyCash->id) . "?redirect=" . route('petty-cash.index') . "&eloremedotgs=1");
        return redirect()->route('petty-cash.index')->with('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return \Illuminate\Http\Response
     */
    public function destroy(PettyCash $pettyCash)
    {
        $pettyCash->ledgers()->delete();
        $pettyCash->delete();
        flash()->success('Petty Cash deleted successfully');
        return back();
    }
}
