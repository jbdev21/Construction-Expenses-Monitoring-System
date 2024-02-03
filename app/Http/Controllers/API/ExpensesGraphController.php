<?php

namespace App\Http\Controllers\API;

use App\Models\Expense;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ExpensesGraphController extends Controller
{
    function expenses(Request $request){
        $data = array();
        foreach(config('system.category.expenses') as $expenseCategory){
           $expense = Expense::select(array(
                DB::raw('DATE(`created_at`) as `date`'),
                DB::raw('SUM(amount) as `price_amount`')
            ))
            ->where('type', Str::lower($expenseCategory))
            ->when($request->project_id, function ($query) use ($request) {
                $query->where('expensable_type', "App\Models\Project")
                    ->where('expensable_id', $request->project_id);
            })
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get()
            ->map(function($q){
                return [$q->date, $q->price_amount];
            });
        
            array_push($data, [
                'name' => Str::plural($expenseCategory),
                'data' => $expense
            ]);
        }
        return response()->json($data);
    }
}
