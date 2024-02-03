<?php

namespace App\Models;

use App\Traits\HasImage;
use Laravel\Scout\Searchable;
use App\Traits\HasCategoryTrait;
use App\Traits\HasExpenses;
use App\Traits\HasLogsTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipment extends Model implements Auditable
{
    use HasFactory,HasCategoryTrait, Searchable, HasExpenses, \OwenIt\Auditing\Auditable, HasImage, HasLogsTrait;

    public $asYouType = true;

    public function toSearchableArray()
    {
        $array = $this->toArray();
        return $array;
    }

    protected $table = "equipments";

    protected $guarded = [];

}
