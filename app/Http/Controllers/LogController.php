<?php

namespace App\Http\Controllers;

use App\Log;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    /**
     * Get page logs.
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

        $count = (int)$request->count ? ($request->count > 100 ? 100 : (int)$request->count) : 25;

        return json_encode($logs->paginate($count));
    }

    public static function log($module, $icon, $desc, $refID = null)
    {
        Log::insert([
            'user_id' => Auth::user()->id,
            'module'  => $module,
            'ref_id'  => $refID,
            'icon'    => $icon,
            'desc'    => $desc,
        ]);
    }

    public static function logAdd($action, $desc, $refID = null)
    {
        self::log($action, 'add', $desc, $refID);
    }

    public static function logEdit($action, $desc, $refID = null)
    {
        self::log($action, 'edit', $desc, $refID);
    }

    public static function logView($action, $desc, $refID = null)
    {
        self::log($action, 'remove_red_eye', $desc, $refID);
    }

    public static function logOther($action, $desc, $refID = null)
    {
        self::log($action, 'input', $desc, $refID);
    }
}
