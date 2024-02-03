<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

class PettyCash extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable, Searchable;

    protected $guarded = [];
    
    public function toSearchableArray()
    {
        $array = $this->toArray();
        return $array;
    }

    protected $dates = [
        'effectivity_date'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function ledgers(){
        return $this->hasMany(Ledger::class);
    }


    public function expenses(){
        return $this->hasMany(Expense::class);
    }


    public function project(){
        return $this->belongsTo(Project::class);
    }


    public function personnel(){
        return $this->belongsTo(Personnel::class);
    }


    public function jsonLedgers(){
        return $this->ledgers()->get()->map(function($q){
            return [
                'item' => $q->description,
                'amount' => $q->amount,
                'category' => $q->category,
            ];
        });
    }
}
