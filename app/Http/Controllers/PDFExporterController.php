<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PDFExporterController extends Controller
{
    function projectExpensesExport(Request $request, $id){
        $project = Project::find($id);
        $query = DB::table("expenses")
                    ->where("project_id", $id)
                    ->orderBy('effectivity_date', 'DESC')->orderBy("id", 'DESC');

        if ($request->q) {
            $query->where('items', 'LIKE', '%' . $request->q . '%');
        }

        if ($request->date_from && !$request->date_to) {

            $start = Carbon::parse($request->date_from);
            $end = Carbon::parse($request->date_to);

            $query->whereBetween('created_at', [$start->format('Y-m-d'), now()->addDay(1)])->get();
        }

        if ($request->date_from && $request->date_to) {

            $start = Carbon::parse($request->date_from);
            $end = Carbon::parse($request->date_to);

            $query->whereBetween('created_at', [$start->format('Y-m-d'), $end->addDay(1)->format('Y-m-d')])->get();
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $laborQuery     = clone $query;
        $totalLabor     = $laborQuery->where('type', 'labor')->sum('amount');

        $othersQuery    = clone $query;
        $totalOthers    = $othersQuery->where('type', 'others')->sum('amount');

        $rentalsQuery   = clone $query;
        $totalRentals   = $rentalsQuery->where('type', 'rental equipment')->sum('amount');

        $materialQuery  = clone $query;
        $totalMaterial  = $materialQuery->where('type', 'material')->sum('amount');

        $forSum = clone $query;
        $sum = $forSum->sum("amount");
        
        $expenses   = $query->get();
        return \PDF::loadView('pdf-export.project-expenses', compact('project', 'expenses', 'totalLabor', 'totalOthers', 'totalMaterial', 'totalRentals', 'sum'))
                ->setOption([
                    'dpi' => 150, 
                    'defaultFont' => 'sans-serif'
                    ])
                ->setPaper('a4', 'landscape')
                ->stream();
        return view('pdf-export.project-expenses', compact('project', 'expenses', 'totalLabor', 'totalOthers', 'totalMaterial', 'totalRentals', 'sum'));
    }
}
