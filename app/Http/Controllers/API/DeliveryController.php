<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    function typeList(){
        return [
            'Project', 
            'Warehouse'
        ];
    }
}
