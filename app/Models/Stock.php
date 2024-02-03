<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];
    
    function material(){
        return $this->belongsTo(Material::class);
    }
    
    function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }
}
