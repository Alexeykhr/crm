<?php

namespace App\Http\Controllers;

use App\Log;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    /**
     * Get logs page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $me = Auth::user();

        if (! $me->role->acs_log) {
            return abort(404);
        }

        $logs = Log::with(['user' => function ($q) {
            $q->select('id', 'name');
        }])->orderBy('date', 'desc');

        return view('logs.index', [
            'me'   => $me,
            'logs' => $logs->paginate(25),
        ]);
    }

    /**
     * For getting logs through axios.
     *
     * @param Request $request
     *
     * @return array
     */
    public function getLogs(Request $request)
    {
        if (! Auth::user()->role->acs_log) {
            return abort(404);
        }

        $logs = Log::with(['user' => function ($q) {
            $q->select('id', 'name');
        }])->orderBy('date', 'desc');

        if (! empty($request->q)) {
            $logs->whereHas('user', function ($query) use ($request) {
                $query->select('id')->where('name', 'LIKE', '%' . $request->q . '%');
            });
        }

        if (! empty($request->module)) {
            $logs->where('module', '=', $request->module);
        }

        if (! empty($request->action)) {
            $logs->where('action', '=', $request->action);
        }

        $count = in_array((int)$request->count, [10, 25, 50, 75, 100]) ? (int)$request->count : 25;

        return json_encode($logs->paginate($count));
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

    public static function logAdd($module, $desc, $refID = null)
    {
        self::log($module, 'Створення', $desc, $refID);
    }

    public static function logDelete($module, $desc, $refID = null)
    {
        self::log($module, 'Видалення', $desc, $refID);
    }

    public static function logEdit($module, $desc, $refID = null)
    {
        self::log($module, 'Редагування', $desc, $refID);
    }

    public static function logView($module, $desc, $refID = null)
    {
        self::log($module, 'Перегляд', $desc, $refID);
    }

    public static function logTransfer($module, $desc, $refId = null)
    {
        self::log($module, 'Трансфер', $desc, $refId);
    }

    public static function logOther($module, $desc, $refID = null)
    {
        self::log($module, 'Інше', $desc, $refID);
    }
}
