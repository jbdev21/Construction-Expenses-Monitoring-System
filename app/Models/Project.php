<?php

namespace App\Models;

use Str;
use Laravel\Scout\Searchable;
use App\Traits\HasCategoryTrait;
use App\Traits\HasDeliveryTrait;
use App\Traits\HasDocumentTrait;
use App\Traits\HasExpenses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model implements Auditable
{
    use HasFactory,HasCategoryTrait, HasDeliveryTrait, Searchable, \OwenIt\Auditing\Auditable, HasDocumentTrait;

    public $asYouType = true;

    public function toSearchableArray()
    {
        $array = $this->toArray();
        return $array;
    }

    public function expenses(){
        return $this->hasMany(Expense::class);
    }

    public function activities(){
        return $this->hasMany(ProjectActivity::class);
    }

    public function subContracts(){
        return $this->hasMany(SubContract::class);
    }

    protected $dates = [
        'expiry_date',
        'ntp_date',
        ];

    protected $guarded = [];

    public function materials()
    {
        return $this->hasMany(ProjectMaterial::class);
    }

    public function deductions(){
        return $this->belongsToMany(Deduction::class)->withTimestamps();
    }

    public function priceRevisions(){
        return $this->hasMany(ProjectPriceRevision::class);
    }

    public function monthlyAchievements(){
        return $this->hasMany(MonthlyAchievement::class)->orderBy("complete_month_date");
    }
    
    public function accomplishmentItems(){
        return $this->hasMany(AccomplishmentItem::class);
    }
    
    public function accomplishmentItemsWithMonthly(){
        return $this->hasMany(AccomplishmentItem::class);
    }

    public function deductedAmountPerItem($item)
    {
        $deduction = is_numeric($item) ? $this->deductions()->where('deductions.id', $item)->first() : $this->deductions()->whereName($item)->first();
        if($deduction){
            if($deduction->type == "percentage"){
                if(Str::contains($deduction->name, "VAT")){
                    return round(($this->contract_amount / 1.12) * ($deduction->figure / 100), 2); 
                }
                //get percentage of items to contract amount per item
                return round($this->contract_amount * ($deduction->figure / 100), 2);
            }
            return $deduction->figure;
        }

        return null;
    }

    public function totalMaterialsPrice()
    {
        return $this->materials()->sum('total_price');
    }

    // public function expenses(){
    //     return $this->hasMany(Expense::class);
    // }

    public function sumExpenses($type = null){
        if($type){
            $prevExpensesColumn = formatTypeForPreviousExpensesToColumn($type);
            return $this->expenses()->whereType(strtolower($type))->sum('amount') + $this->$prevExpensesColumn;
        }

        return $this->expenses()->sum('amount') + $this->getAllPreviousExpenses();
    }


    public function balance($withDeductions = true  ){
        return round($this->contract_amount - $this->sumExpenses(), 2);
    }


    function getAllPreviousExpenses(){
        return $this->old_expense_labors + $this->old_expense_materials + $this->old_expense_rentals + $this->old_expense_others;
    }
}
