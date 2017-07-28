<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BirthdayController extends Controller
{
    public function index()
    {
        $me = Auth::user()->load('role');

        if (!$me->role->acs_birthday) {
            return abort(404);
        }

        return view('birthday.index', [
            'me' => $me,
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
