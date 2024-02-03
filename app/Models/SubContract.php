<?php

namespace App\Models;

use App\Traits\HasExpenses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubContract extends Model
{
    use HasFactory, HasExpenses;

    protected $guarded = [];


    public function sumExpenses($type = null){
        if($type){
            return $this->expenses()->whereType($type)->sum('amount');
        }
        return $this->expenses()->sum('amount');
    }

    function remainingAmount($type = null){
        return $this->contract_amount - $this->sumExpenses($type);
    }
}
