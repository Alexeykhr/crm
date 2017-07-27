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
        $me = Auth::user()->load('role');

        if (!$this->access($me->role->acs_user, 'view')) {
            return abort(404);
        }

        $users = User::with(['role', 'job']);

        if (!empty($request->q)) {
            $users->where('name', 'LIKE', '%' . $request->q . '%')
                ->orWhere('nick', 'LIKE', '%' . $request->q . '%');
        }

        if (!empty($request->role) && $request->role > 0 && $me->role->level > 5) {
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

        $count = (int)$request->count ? ($request->count > 100 ? 100 : (int) $request->count) : 20;

        return view('users.index', [
            'me'    => $me,
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
        $me = Auth::user();
        $id = (int)$id;

        if ($me->id === $id) {
            return $this->profile();
        }

        $user = User::with(['role', 'job'])
            ->where('id', '=', $id)
            ->firstOrFail();

        $me->load('role');

        if (!$this->access($me->role->acs_user, 'view')) {
            return abort(404);
        }

        return view('users.user', [
            'me'   => $me,
            'user' => $user,
            'edit' => $this->access($me->role->acs_user, 'edit') && $me->role->level > $user->role->level,
        ]);
    }

    /**
     * Get your own profile.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        $me = Auth::user()->load('role', 'job');

        return view('users.user', [
            'me'   => $me,
            'edit' => $me->role->level > 4
        ]);
    }

    /**
     * Create a new user.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $me = Auth::user()->load('role');

        if (!$this->access($me->role->acs_user, 'create')) {
            return abort(404);
        }

        return view('users.create', [
            'me'    => $me,
            'roles' => Role::get(),
            'jobs'  => Job::get(),
        ]);
    }
}
