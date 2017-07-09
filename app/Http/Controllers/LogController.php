<?php

namespace App\Http\Controllers;

use App\Log;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index()
    {
        // TODO add filters
        
        $logs = Log::paginate(20);

        return view('logs.index', [
            'me'   => Auth::user()->load('role'),
            'logs' => $logs,
        ]);
    }

    public static function log($module, $action, $desc, $refID = null)
    {
        Log::insert([
            'user_id' => Auth::user()->id,
            'module' => $module,
            'action' => $action,
            'ref_id' => $refID,
            'desc'  => $desc,
            'date'  => date('m-d-y H:i:s'),
        ]);
    }

    public static function logAuth()
    {
        self::log('auth', 'enter', 'Користувач увійшов в систему');
    }
}
