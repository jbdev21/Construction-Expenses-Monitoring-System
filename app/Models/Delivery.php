<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'effectivity_date' => 'datetime:Y-m-d'
    ];


    function materials(){
        return $this->belongsToMany(Material::class)
            ->using(DeliveryMaterialPivot::class)
            ->withPivot("quantity", "price_per_unit", "amount", 'remarks')
            ->withTimestamps();
    }

    function setFromAndTo($from, $to){
        $this->deliverableFrom()
            ->associate($from)
            ->deliverableTo()->associate($to);

        return $this;
    }


    public function deliverableTo()
    {
        return $this->morphTo('deliverable_to');
    }


    public function deliverableFrom()
    {
        return $this->morphTo('deliverable_from');
    }
}
