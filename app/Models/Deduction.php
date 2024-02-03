<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deduction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    function sign(){
        if($this->type == "percentage"){
            return $this->figure . "%"; 
        }

        return toPeso($this->figure);
    }
}
