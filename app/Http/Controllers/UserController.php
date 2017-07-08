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
        if (Auth::user()->role->acs_user < 1) {
            return view('error.access');
        }

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
        $id = (int)$id;

        if (Auth::user()->id === $id) {
            return $this->profile();
        }

        $access = Auth::user()->role->acs_user;

        if ($access % 2 == 0) {
            return view('error.access');
        }

        $user = User::where('id', '=', $id)->firstOrFail();

        return view('users.user', [
            'user' => $user,
            'edit' => $this->access($access, 'edit')
        ]);
    }

    /**
     * Get your own profile.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        return view('users.user', [
            'user' => Auth::user(),
            'edit' => Auth::user()->role->acs_profile > 0
        ]);
    }

    /**
     * Get access.
     *
     * @param int $access
     * @param string $action
     *
     * @return bool
     */
    private function access($access, $action)
    {
        if ($action === 'create') {
            return $access > 3;
        }

        if ($action === 'edit') {
            return in_array($access, [2, 3, 6, 7]);
        }

        if ($action === 'view') {
            return $access % 2 == 1;
        }

        return false;
    }
}
