<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Personnel extends Model
{
    use HasFactory, Searchable;

    public function toSearchableArray()
    {
        $array = $this->toArray();
        return $array;
    }

    protected $guarded = [];

    function cashAdvances(){
        return $this->belongsTo(CashAdvance::class);
    }
}
