<?php

namespace App\Http\Controllers;

use App\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public static function add($module, $action, $desc, $refID = null)
    {
        // TODO more methods, add date format DB
        Log::insert([
            'user_id' => Auth::user()->id,
            'module' => $module,
            'action' => $action,
            'ref_id' => $refID,
            'desc'  => $desc,
//            'date'  => date(''),
        ]);
    }
}
