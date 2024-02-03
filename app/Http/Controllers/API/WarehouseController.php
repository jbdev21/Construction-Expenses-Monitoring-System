<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Motorpool;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    function selectData(Request $request){
        return Warehouse::search($request->key)
            ->take(5)
            ->get()
            ->map(function($query){
                return [
                    'label' => $query->name,
                    'code' => $query->id,
                    'class' => Warehouse::class
                ];
            });
    }

    function search(Request $request){
        return Warehouse::where('name', 'LIKE', $request->key . '%')
                ->take(8)
                ->get()
                ->map(function($q){
                    return [
                        'code' => $q->id,
                        'label' => $q->name,
                        'price' => $q->rate_per_hour
                    ];
                });
    }

    function select2(Request $request){
        return Warehouse::where('name', 'LIKE', $request->key . '%')
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
