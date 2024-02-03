<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    function select2(Request $request){
        return Personnel::where('name', 'LIKE', '%' . $request->searchTerm . '%')
                ->take(8)
                ->get()
                ->map(function($q){
                    return [
                        'id' => $q->id,
                        'text' => $q->name,
                    ];
                });
    }
}
