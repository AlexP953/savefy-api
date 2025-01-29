<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

abstract class Controller
{
    public function LogError($message, $e = null){
        Log::error($message);
        return response()->json(['error' => 'Ha ocurrido un error'], 500);
    }
}
