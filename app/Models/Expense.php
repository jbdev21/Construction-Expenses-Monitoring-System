<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $casts = [
        'effectivity_date' => 'date:Y-m-d'
    ];

    protected $guarded = [];
    
    function project(){
        return $this->belongsTo(Project::class);
    }

    function expensable(){
        return $this->morphTo();
    }
    
}
