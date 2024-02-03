<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyAchievement extends Model
{
    use HasFactory;

    protected $guarded = [];

    function project(){
        return $this->belongsTo(Project::class);
    }

    function accomplishmentAchievements(){
        return $this->hasMany(AccomplishmentAchievement::class);
    }
}
