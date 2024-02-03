<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Audit extends Model
{
    use HasFactory, Searchable;

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function toSearchableArray()
    {
        $array = $this->toArray();
        return $array;
    }

    function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
