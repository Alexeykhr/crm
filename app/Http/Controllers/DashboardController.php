<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $me = User::with('role')
            ->where('id', '=', Auth::id())
            ->first();

        return view('dashboard', [
            'me' => $me,
        ]);
    }
}
