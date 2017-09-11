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

        $month = Carbon::now()->month;

        $users = User::select('id', 'name', 'birth', 'photo')
            ->whereMonth('birth', '=', $month)
            ->get();

        return view('calendar.index', [
            'me'    => $me,
            'year'  => Carbon::now()->year,
            'users' => $users,
            'month' => $month,
        ]);
    }

    public function getCalendar(Request $request)
    {
        if (! $this->access(Auth::user()->role->acs_calendar, 'view')) {
            return abort(404);
        }

        $users = User::select('id', 'name', 'birth', 'photo')
            ->whereMonth('birth', '=', (int)$request->month)
            ->get();

        return json_encode($users);
    }
}
