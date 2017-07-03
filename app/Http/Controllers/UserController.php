<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'nick', 'name', 'role_id', 'phone', 'work_phone', 'email', 'work_email', 'active')
            ->with(['role'])
            ->where('delete', '=', 0)
            ->paginate(10); // temporary

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
