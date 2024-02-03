<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;

class AccomplishmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'item_number' => $this->item_number,
            'unit' => $this->unit,
            'weight' => $this->weight,
            'quantity' => $this->quantity,
            'unit_cost' => $this->unit_cost,
            'total_contract_cost' => $this->total_contract_cost,
            'parent_id' => $this->parent_id,
            'monthly_achievements' => $this->accomplishmentAchievements(), 

            // revissions
            'revised_quantity' => $this->revised_quantity,
            'revised_unit_cost' => $this->revised_unit_cost,
            'revised_contract_cost' => $this->revised_contract_cost,
            
        ];
    }

    function accomplishmentAchievements(){
        return $this->project->monthlyAchievements->map(function($q){
            return [
                'id' => $q->id,
                'label' => $q->label,
                'value' => optional($q->accomplishmentAchievements()->where("accomplishment_item_id", $this->id)->first())->achievement ?? 0
            ];
        });
    }
}
