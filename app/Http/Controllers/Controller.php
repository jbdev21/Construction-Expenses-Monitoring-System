<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function responseJsonOK($message, $data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 200,
            'errors' => [], 
            'data' => $data ?? (object)[],
            'message' => $message
        ], 200);
    }

    function responseJsonValidationFailed(String $message, $errors): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 422,
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

}
