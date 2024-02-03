<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ledger;
use App\Models\Expense;
use App\Models\Project;
use App\Models\Deduction;
use App\Models\CashOnHand;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    function project(Request $request)
    {
        $query = Project::where('project_year', now()->format('Y'))->orderBy('name');

        if ($request->year) {
            $query = Project::where('project_year', $request->year)->orderBy('name');
        }

        if ($request->status != '') {
            $query->whereStatus($request->status ?? 'ongoing');
        }

        $projects = $query->with(['category', 'priceRevisions'])->paginate(15);
        $deductions = Deduction::all();
        return view('report.project', compact("projects", 'deductions'));
    }

    function expenses(Request $request)
    {
        //if current day then request day
        $date = $request->date ? Carbon::parse($request->date) : now();

        $query = Expense::whereDate('created_at', $date->format('Y-m-d'))->with('expensable')->with('project');

        if ($request->date) {
            $query->whereDate('created_at', $date->format('Y-m-d'));
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $expenses = $query->get();

        return view('report.expenses', compact("expenses"));
    }

    function casher(Request $request)
    {
        $cashOnhand = CashOnHand::first();

        // dates
        $date = $request->date ? Carbon::parse($request->date) : now();
        $forSub = $request->date ? Carbon::parse($request->date) : now();  
        
        // getting the last cash on hand even for money (missing sunday)
        $lastCashOnHand = optional(Ledger::whereDate('effectivity_date', '<', $date)
            ->orderBy('effectivity_date', 'DESC')
            ->whereIn('type', ['debit','credit'])
            ->orderBy('id', 'DESC')
            ->first())->onhand_amount ?? 0;

        // expenses in requested day - only debit
        $expenses = Ledger::whereDate('effectivity_date', $date->format('Y-m-d'))
            ->where('type', 'debit')
            ->orderBy('effectivity_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->with('pettyCash')->get();

        // getting cashins - insert last cash on hand
        $cashIns = Ledger::whereDate('effectivity_date', $date->format('Y-m-d'))
                    ->where('type', 'credit');
                    // return $cashIns->sum('amount');
        $todayTotalCashIn = $cashIns->sum("amount") + $lastCashOnHand;

         
        // the very last ledger as basis of onhand amound
        $todayCashOnHand = $todayTotalCashIn - $expenses->sum("amount");
        $nextDate = $request->date 
                        ? route("report.ledger-summary", ['date' => Carbon::parse($request->date)->addDays(1)->format('Y-m-d')]) 
                        : '#';
        $prevDate = $request->date 
                        ? route("report.ledger-summary", ['date' => Carbon::parse($request->date)->subDays(1)->format('Y-m-d')]) 
                        : route("report.ledger-summary", ['date' => now()->subDays(1)->format('Y-m-d')]);
        
        return view('report.casher', compact("nextDate", "prevDate", "lastCashOnHand", 'expenses', 'cashIns', 'todayTotalCashIn', 'cashIns', 'todayCashOnHand'));
    }
    
}
