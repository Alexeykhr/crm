<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_calendar, 'view')) {
            return abort(404);
        }

        $users = User::select('name', 'birth')
            ->whereMonth('birth', '=', Carbon::now()->month)
            ->get();

        return view('calendar.index', [
            'me'    => $me,
            'users' => $users,
        ]);
    }

    public function getMonth($month)
    {
        $users = User::select('name', 'birth')
            ->whereMonth('birth', '=', $month)
            ->get();

        return json_encode($users);
    }
}
