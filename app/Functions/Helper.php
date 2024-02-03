<?php

use Illuminate\Support\Arr;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;


function activeMenu($segments, $returnShow = true, $position = 1){
    $boolean = false;
    if(is_array($segments)){
        $boolean = in_array(Request::segment($position), $segments);
    }else{
        $boolean = Request::segment($position) == $segments;
    }

    if($returnShow){
        return $boolean ? 'show' : '';
    }

    return false;
}


function formatTypeForPreviousExpensesToColumn($type){
    switch($type){
        case 'Labor':
            return 'old_expense_labors';
        case 'Material':
            return 'old_expense_materials';
        case 'Rental Equipment':
            return 'old_expense_rentals';
        default:
            return 'old_expense_others';
    }
}


function toPeso($amount){
    return "Php ".number_format($amount, 2);
}


function resizeImage($image, $width, $height, $isAspectRatio = true){
    $realPath = Storage::disk('public')->path($image);
    $img = Image::make($realPath); // should be absolute path of the file
    $img->resize($width, $height, function ($constraint) use ($isAspectRatio) {
        if($isAspectRatio){
            $constraint->aspectRatio();
        }
    })->save($realPath);

    return $img;
}

function arrayToLower($array){
    for($i = 0; $i < count($array); $i++){
        $array[$i] = strtolower($array[$i]);
    }
    return $array;
}


function getYearRange(){
    $lastYear = config("system.year_selection.starting_year");
    $yearArray = array();

    for($lastYear; now()->format("Y")  >= $lastYear; $lastYear++){
        array_push($yearArray, $lastYear);
    }

    return array_reverse($yearArray);
}