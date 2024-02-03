<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Audit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    function index(Request $request)
    {
        $query = Audit::with('user')->latest();

        if ($request->q) {
            $query = $query->where('new_values', 'LIKE', '%' . $request->q . '%');
        }

        if($request->event){
            $query->where('event', 'LIKE', '%' . $request->event . '');
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

        $audits = $query->paginate(30);

        return view('log.index', compact('audits'));
    }
}
