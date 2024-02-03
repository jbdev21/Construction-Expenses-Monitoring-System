<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MaterialReStockPivot extends Pivot
{
    protected $table = 'material_re_stock';

    public $incrementing = true;

}
