<?php
namespace App\Models;

use Laravel\Scout\Searchable;
use App\Traits\HasCategoryTrait;
use App\Traits\HasExpenses;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model implements Auditable
{
    use HasFactory, HasCategoryTrait, Searchable, \OwenIt\Auditing\Auditable, HasExpenses;

    public $asYouType = true;

    protected static function booted()
    {
        static::addGlobalScope('ancient', function (Builder $builder) {
            $builder->with('category');
        });
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();
        return $array;
    }

    protected $guarded = [];

    function stocks(){
        return $this->hasMany(Stock::class);
    }

    function stockInWarehouse($warehouseId){
        return $this->hasOne(Stock::class)->where('warehouse_id', $warehouseId)->first();
    }

    function overAllStockCount(){
        return $this->stocks()->sum('quantity');
    }

}
