<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DeliveryMaterialPivot extends Pivot
{
    protected $table = 'delivery_material';

    public $incrementing = true;
}
