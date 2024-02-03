<?php

namespace App\Observers;

use App\Models\Delivery;
use App\Models\DeliveryMaterialPivot;
use App\Models\Expense;
use App\Models\Material;
use App\Models\Project;
use App\Models\Warehouse;

class DeliveryMaterialPivotObserver
{
    /**
     * Handle the DeliveryMaterialPivot "created" event.
     *
     * @param  \App\Models\DeliveryMaterialPivot  $deliveryMaterialPivot
     * @return void
     */
    public function created(DeliveryMaterialPivot $deliveryMaterialPivot)
    {
        
        $delivery = Delivery::find($deliveryMaterialPivot->delivery_id);
        $material = Material::find($deliveryMaterialPivot->material_id);
        $delivery->increment("amount", $deliveryMaterialPivot->amount);

        if($delivery->deliverable_to_type == Project::class){
            // add to expense
            $expense = Expense::create([
                'items'             => strtoupper($deliveryMaterialPivot->quantity . " " . $material->unit . ' of ' . $material->name),
                'amount'            => $deliveryMaterialPivot->amount,
                'unit_quantity'     => $deliveryMaterialPivot->quantity,
                'unit_price'        => $deliveryMaterialPivot->price_per_unit,
                'type'              => "material",
                'effectivity_date'  => $delivery->effectivity_date
            ]);
            
            $material->expenses()->save($expense);
            $delivery->deliverableTo->expenses()->save($expense);
        }

        
        if($delivery->deliverable_from_type == Warehouse::class){
            $warehouse = Warehouse::find($delivery->deliverable_from_id);
            $stock = $warehouse->stocks()->where("material_id", $material->id)->first();

            if($stock){
                if($stock->quantity < $deliveryMaterialPivot->quantity){
                    throw new \Exception($material->name . " not enough stock");
                }

                $stock->decrement("quantity", $deliveryMaterialPivot->quantity); // decrement the quantity from stock

            }else{
                throw new \Exception($material->name . " is out of stock in ". $warehouse->name);
            }
        }
    }

    /**
     * Handle the DeliveryMaterialPivot "updated" event.
     *
     * @param  \App\Models\DeliveryMaterialPivot  $deliveryMaterialPivot
     * @return void
     */
    public function updated(DeliveryMaterialPivot $deliveryMaterialPivot)
    {
        //
    }

    /**
     * Handle the DeliveryMaterialPivot "deleted" event.
     *
     * @param  \App\Models\DeliveryMaterialPivot  $deliveryMaterialPivot
     * @return void
     */
    public function deleted(DeliveryMaterialPivot $deliveryMaterialPivot)
    {
        //
    }

    /**
     * Handle the DeliveryMaterialPivot "restored" event.
     *
     * @param  \App\Models\DeliveryMaterialPivot  $deliveryMaterialPivot
     * @return void
     */
    public function restored(DeliveryMaterialPivot $deliveryMaterialPivot)
    {
        //
    }

    /**
     * Handle the DeliveryMaterialPivot "force deleted" event.
     *
     * @param  \App\Models\DeliveryMaterialPivot  $deliveryMaterialPivot
     * @return void
     */
    public function forceDeleted(DeliveryMaterialPivot $deliveryMaterialPivot)
    {
        //
    }
}
