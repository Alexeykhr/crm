<?php

namespace App\Http\Controllers;

use App\Job;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Get page users with filters.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 0 < count <= 100
        $count = (int)$request->count ? ($request->count > 100 ? 100 : (int) $request->count) : 10;

        $users = User::with(['role', 'job']);

        if (!empty($request->q)) {
            $users->where('name', 'LIKE', '%' . $request->q . '%');
        }

        if (!empty($request->role) && $request->role > 0) {
            $users->where('role_id', '=', (int) $request->role);
        }

        if (!empty($request->job) && $request->job > 0) {
            $users->where('job_id', '=', (int) $request->job);
        }

        if (!empty($request->active)) {
            $users->where('active', '=', $request->active > 0);
        }

        if (!empty($request->delete)) {
            $users->where('delete', '=', $request->delete > 0);
        } elseif (!isset($request->delete)) {
            $users->where('delete', '=', 0);
        }

        return view('users.index', [
            'users' => $users->paginate($count),
            'roles' => Role::get(),
            'jobs'  => Job::get(),
        ]);
    }

    /**
     * Get an employee profile
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user($id)
    {
        $user = User::with('role', 'job')->where('id', '=', $id)->first();

        dd($user);

        return view('users.user', ['user' => $user]);
    }

    /**
     * Get your own profile.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        return view('users.profile', ['user' => Auth::user()]);
    }
}
