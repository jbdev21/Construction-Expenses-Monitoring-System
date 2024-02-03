<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Project;
use App\Models\Deduction;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    function projectSummary(Request $request)
    {
        $query = Project::with('category')->where('project_year', now()->format('Y'))->orderBy('name');

        if ($request->year) {
            $query = Project::with('category')->where('project_year', $request->year)->orderBy('name');
        }

        if ($request->status != '') {
            $query->whereStatus($request->status ?? 'ongoing');
        }

        $projects = $query->paginate(15);
        $deductions = Deduction::all();
        return \PDF::loadView('printables.project-summary', compact("projects", 'deductions'))
                    ->setPaper('legal')
                    ->setOrientation('landscape')
                    ->setOption('minimum-font-size', 9)
                    ->setOption('margin-bottom', 5)
                    ->setOption('margin-top', 5)
                    ->setOption('margin-left', 5)
                    ->setOption('margin-right', 5)
                    ->inline();
    }

    function expenses(Request $request)
    {
        //if current day then request day
        $date = $request->date ? Carbon::parse($request->date) : now();

        $query = Expense::whereDate('created_at', $date->format('Y-m-d'))->with('expensable');

        if ($request->date) {
            $query->whereDate('created_at', $date->format('Y-m-d'));
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $expenses = $query->limit(5)->get();



        return \PDF::loadView('printables.expenses', compact("expenses"))
                    ->render();
                    // ->setPaper('legal')
                    // ->setOrientation('landscape')
                    // ->setOption('minimum-font-size', 9)
                    // ->setOption('margin-bottom', 5)
                    // ->setOption('margin-top', 5)
                    // ->setOption('margin-left', 5)
                    // ->setOption('margin-right', 5)
                    // ->inline();
    }
}
