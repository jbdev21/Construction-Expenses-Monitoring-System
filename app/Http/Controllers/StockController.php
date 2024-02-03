<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    function store(Request $request){
        $this->validate($request, [
            'material_id' => ['required', 'exists:materials,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'quantity' => ['required']
        ]);

        $stock = Stock::firstOrCreate([
                    'material_id' => $request->material_id,
                    'warehouse_id' => $request->warehouse_id
                ]);  
        
        $stock->increment('quantity', $request->quantity);
        flash()->success("Adding stock successfully!");
        return back();
    }
}
