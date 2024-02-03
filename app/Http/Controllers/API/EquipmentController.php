<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    function search(Request $request){
        return Equipment::where('name', 'LIKE', $request->key . '%')
                ->take(8)
                ->get()
                ->map(function($q){
                    return [
                        'code' => $q->id,
                        'label' => $q->name,
                        'price' => $q->rate_per_hour,
                        'daily' => $q->daily_rate ?? 0
                    ];
                });
    }
}
