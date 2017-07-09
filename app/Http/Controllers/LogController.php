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
        $me = Auth::user()->load('role');

        if ($me->role->level < 7) {
            return abort(404);
        }

        // TODO: add filters
        
        $logs = Log::with('user')
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('logs.index', [
            'me'   => $me,
            'logs' => $logs,
        ]);
    }

    public static function log($module, $action, $desc, $refID = null)
    {
        Log::insert([
            'user_id' => Auth::user()->id,
            'module'  => $module,
            'action'  => $action,
            'ref_id'  => $refID,
            'desc'    => $desc,
        ]);
    }

    public static function logAuth()
    {
        self::log('auth', 'Авторизація', 'Користувач увійшов в систему');
    }
}
