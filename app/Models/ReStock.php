<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReStock extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'effectivity_date' => 'date:Y-m-d'
    ];

    function materials(){
        return $this->belongsToMany(Material::class)
            ->using(MaterialReStockPivot::class)
            ->withPivot("quantity", 'old_quantity', 'new_quantity')
            ->withTimestamps();
    }


    function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }


}
