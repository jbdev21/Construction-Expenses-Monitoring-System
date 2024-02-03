<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Motorpool extends Model implements Auditable
{
    use HasFactory, Searchable, \OwenIt\Auditing\Auditable;

    public $asYouType = true;

    public function toSearchableArray()
    {
        $array = $this->toArray();
        return $array;
    }

    protected $guarded = [];
}
