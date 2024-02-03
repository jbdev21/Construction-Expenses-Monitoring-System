<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvance extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'effectivity_date' => 'date:Y-m-d'
    ];


    function personnel(){
        return $this->belongsTo(Personnel::class);
    }
}

