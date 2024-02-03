<?php

namespace App\Http\Controllers;

use App\Models\CashOnHand;
use App\Models\Ledger;
use App\Models\Personnel;
use App\Models\PettyCash;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    function index(){

    }

    function create(){
        $personnels = Personnel::all();
        return view('salary.create', compact('personnels'));
    }

    function store(Request $request){
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

        $data = [];

        if($request->items){
            foreach($request->items as $index => $item){
                $helperTotalAmount += $request->amount[$index];

                // adding data (as helpers) in the data column
                $data['helpers'][] = [
                    'item' => $item,
                    'amount' => $request->amount[$index]
                ];
            }
            $helperTextExtension = " WITH " . count($request->items) . " HELPER(s) " . toPeso($helperTotalAmount);
        }

        $data['salary'] = [
            'item' => 'salary',
            'amount' => $request->salary
        ];

        
        //store petty_cash
        $petty_cash = new PettyCash();
        $petty_cash->payee = \Str::upper($personnel->name);
        $petty_cash->personnel_id = $request->personnel_id;
        $petty_cash->effectivity_date = $request->effectivity_date;
        $petty_cash->data = $data;
        $petty_cash->type = "salary";
        $petty_cash->save();


        if($request->deduction){
            $deductionText = "(CA Deduction:" . toPeso($request->deduction) . ")";
        }
        
        $ledger                     = new Ledger();
        $ledger->petty_cash_id      = $petty_cash->id;
        $ledger->description        = "SALARY " . \Str::upper($personnel->name) . ' ' . $petty_cash->effectivity_date->format('Y-m-d') . 
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
            $ledger->description        = "CASH RETURN FROM " . \Str::upper($personnel->name) . " " . $petty_cash->effectivity_date->format('Y-m-d');// payment
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

    function show(){

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

            if ($ledger->type == "credit") {
                $cashOnhand->increment('amount', $ledger->amount);
            } else {
                $cashOnhand->decrement('amount', $ledger->amount);
            }

            $ledger->update(['onhand_amount' => CashOnHand::first()->amount]);
        }   
    }


    function edit($id){
        $personnels = Personnel::all();
        return view("salary.edit", compact('pettyCash', 'personnels'));
    }

    function update(Request $request, $id){
        $pettyCash = PettyCash::find($id);
        //validate request
        $this->validate($request, [
            'effectivity_date'  => ['required', 'date'],
            'personnel_id'      => ['nullable', 'exists:personnels,id'],
            'salary'            => ['required'],
            'items'             => ['nullable', 'array'],     
            'amount'            => ['nullable', 'array'],     
        ]);

        foreach($pettyCash->ledgers as $ledger){
            $ledger->delete();
        }

        $personnel = Personnel::find($request->personnel_id);
        $helperTotalAmount = 0;
        $helperTextExtension = "";
        $deductionText = "";

        $data = [];

        if($request->items){
            foreach($request->items as $index => $item){
                $helperTotalAmount += $request->amount[$index];

                // adding data (as helpers) in the data column
                $data['helpers'][] = [
                    'item' => $item,
                    'amount' => $request->amount[$index]
                ];
            }
            $helperTextExtension = " WITH " . count($request->items) . " HELPER(s) " . toPeso($helperTotalAmount);
        }

        $data['salary'] = [
            'item' => 'salary',
            'amount' => $request->salary
        ];

        
        //update petty_cash
        $pettyCash->payee = \Str::upper($personnel->name);
        $pettyCash->personnel_id = $request->personnel_id;
        $pettyCash->effectivity_date = $request->effectivity_date;
        $pettyCash->data = $data;
        $pettyCash->type = "salary";
        $pettyCash->amount = 0;
        $pettyCash->save();


        if($request->deduction){
            $deductionText = "(CA Deduction:" . toPeso($request->deduction) . ")";
        }
        
        $ledger                     = new Ledger();
        $ledger->petty_cash_id      = $pettyCash->id;
        $ledger->description        = "SALARY " . \Str::upper($personnel->name) . ' ' . $pettyCash->effectivity_date->format('Y-m-d') . 
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
            $ledger->description        = "CASH RETURN FROM " . \Str::upper($personnel->name) . " " . $pettyCash->effectivity_date->format('Y-m-d');// payment
            $ledger->amount             = $request->deduction;
            $ledger->type               = 'cash-return'; // as default in the DB
            $ledger->effectivity_date   = $request->effectivity_date;
            $ledger->save();
    
            if($request->personnel_id){
                $personnel->decrement("current_cash_advance", $request->deduction);
            }
        }

        //if petty cash effectivity_date provided then update ledger rehistory
            $effectivityDate = $pettyCash->effectivity_date;
            $diff = $effectivityDate->diffInDays(now());

            if ($diff > 0) {
                $this->updateHistory($pettyCash);
            }

        //redirect
        flash()->success('Petty Cash updated successfully');
        return redirect(route('petty-cash.print', $pettyCash->id) . "?redirect=" . route('petty-cash.index') . "&eloremedotgs=1");
    }

}
