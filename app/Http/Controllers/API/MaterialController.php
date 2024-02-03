<?php

namespace App\Http\Controllers\API;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialController extends Controller
{
    function search(Request $request){
        return Material::where('name', 'LIKE', $request->key . '%')
                ->take(8)
                ->get()
                ->map(function($q){
                    return [
                        'code' => $q->id,
                        'label' => $q->name,
                        'price' => $q->price
                    ];
                });
    }

    function select2(Request $request){
        return Material::where('name', 'LIKE', $request->searchTerm . '%')
                ->take(8)
                ->get()
                ->map(function($q){
                    return [
                        'id' => $q->id,
                        'text' => $q->name,
                    ];
                });
    }


    function stockInWarehouse(Request $request){
        $this->validate($request, [
            'warehouse' => 'required|exists:warehouses,id',
            'material' => 'required|exists:materials,id',
        ]);
        $material = Material::find($request->material);
        $warehouse = $request->warehouse;
        return $material->stocks()->where('warehouse_id', $warehouse)->sum('quantity');
    }
}
