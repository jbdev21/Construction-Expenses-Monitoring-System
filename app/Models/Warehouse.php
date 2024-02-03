<?php

namespace App\Models;

use App\Traits\HasDeliveryTrait;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model implements Auditable
{
    use HasFactory, Searchable, HasDeliveryTrait, \OwenIt\Auditing\Auditable;

    public $asYouType = true;

    public function toSearchableArray()
    {
        $array = $this->toArray();
        return $array;
    }

    protected $guarded = [];

    function stocks(){
        return $this->hasMany(Stock::class);
    }
}
