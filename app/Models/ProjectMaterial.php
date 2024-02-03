<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectMaterial extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('ancient', function (Builder $builder) {
            $builder->with('material');
        });
    }

    function project(){
        return $this->belongsTo(Project::class);
    }

    function material(){
        return $this->belongsTo(Material::class);
    }
}
