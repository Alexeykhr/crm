<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'nick', 'name', 'role', 'phone', 'work_phone', 'active')
            ->where('delete', '=', 0)
            ->paginate(50);

        dd($users);

        return view('users.index', ['users' => $users]);
    }

    public function user(User $user)
    {
        dd($user);
    }

    public function profile()
    {
        $user = Auth::user();

        return view('users.profile', ['user' => $user]);
    }
}
