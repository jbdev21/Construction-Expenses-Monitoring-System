<?php

namespace App\Observers;

use App\Models\MaterialReStockPivot;
use App\Models\ReStock;
use App\Models\Stock;

class MaterialReStockPivotObserver
{
    /**
     * Handle the MaterialReStockPivot "created" event.
     *
     * @param  \App\Models\MaterialReStockPivot  $materialReStockPivot
     * @return void
     */
    public function created(MaterialReStockPivot $materialReStockPivot)
    {
        $restock = ReStock::find($materialReStockPivot->re_stock_id);
        
        $stock = Stock::firstOrCreate([
            'material_id' => $materialReStockPivot->material_id,
            'warehouse_id' => $restock->warehouse_id
        ]);  

        $materialReStockPivot->update(['old_quantity' => $stock->quantity ?? 0]);

        $stock->increment('quantity', $materialReStockPivot->quantity);

        $freshStock = $stock->fresh();
        $materialReStockPivot->update(['new_quantity' => $freshStock->quantity]);
    }

    /**
     * Handle the MaterialReStockPivot "updated" event.
     *
     * @param  \App\Models\MaterialReStockPivot  $materialReStockPivot
     * @return void
     */
    public function updated(MaterialReStockPivot $materialReStockPivot)
    {
        //
    }

    /**
     * Handle the MaterialReStockPivot "deleted" event.
     *
     * @param  \App\Models\MaterialReStockPivot  $materialReStockPivot
     * @return void
     */
    public function deleted(MaterialReStockPivot $materialReStockPivot)
    {
        $restock = ReStock::find($materialReStockPivot->re_stock_id);
        
        $stock = Stock::firstOrCreate([
            'material_id' => $materialReStockPivot->material_id,
            'warehouse_id' => $restock->warehouse_id
        ]);  

        $stock->decrement('quantity', $materialReStockPivot->quantity);
    }

    /**
     * Handle the MaterialReStockPivot "restored" event.
     *
     * @param  \App\Models\MaterialReStockPivot  $materialReStockPivot
     * @return void
     */
    public function restored(MaterialReStockPivot $materialReStockPivot)
    {
        //
    }

    /**
     * Handle the MaterialReStockPivot "force deleted" event.
     *
     * @param  \App\Models\MaterialReStockPivot  $materialReStockPivot
     * @return void
     */
    public function forceDeleted(MaterialReStockPivot $materialReStockPivot)
    {
        //
    }
}
