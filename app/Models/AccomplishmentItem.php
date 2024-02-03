<?php

namespace App\Models;

use Illuminate\Contracts\Queue\Monitor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccomplishmentItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'complete_month_date' => 'date'
    ];

    function project(){
        return $this->belongsTo(Project::class);
    }

    function accomplishmentAchievements(){
        return $this->hasMany(AccomplishmentAchievement::class);
    }

    

}
