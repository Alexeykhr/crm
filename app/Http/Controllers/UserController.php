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
     * Get users page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_user, 'view')) {
            return abort(404);
        }

        $users = User::select($this->getPublicColumnByUser())
            ->orderBy('name', 'asc')
            ->where('delete', '=', 0);

        if ($this->access($me->role->acs_role, 'view')) {
            $users->addSelect('role_id')->with('role');
        }

        if ($this->access($me->role->acs_job, 'view')) {
            $users->addSelect('job_id')->with('job');
        }

        return view('users.index', [
            'me'        => $me,
            'jobs'      => Job::orderBy('title', 'asc')->get(),
            'roles'     => Role::orderBy('title', 'asc')->get(),
            'users'     => $users->paginate(25),
            'canCreate' => $this->access($me->role->acs_user, 'create'),
        ]);
    }

    /**
     * Get an employee profile.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $me = Auth::user();
        $id = (int)$id;

        if ($me->id === $id) {
            return $this->profile();
        }

        if (! $this->access($me->role->acs_user, 'view')) {
            return abort(404);
        }

        $user = User::select($this->getPublicColumnByUser())
            ->where('id', '=', $id);

        if ($this->access($me->role->acs_role, 'view')) {
            $user->addSelect('role_id')->with('role');
        }

        if ($this->access($me->role->acs_job, 'view')) {
            $user->addSelect('job_id')->with('job');
        }

        $user = $user->firstOrFail();

        return view('users.profile', [
            'me'      => $me,
            'user'    => $user,
            'canEdit' => $this->access($me->role->acs_user, 'edit') && $user->role && $me->role->level >= $user->role->level,
        ]);
    }

    /**
     * Get your own profile.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        $me = Auth::user();

        if ($this->access($me->role->acs_job, 'view')) {
            $me->load('job');
        }

        return view('users.profile', [
            'me'      => $me,
            'canEdit' => $me->role->acs_profile
        ]);
    }

    /**
     * Create a new user.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_user, 'create')) {
            return abort(404);
        }

        return view('users.create', [
            'me'    => $me,
            'jobs'  => Job::get(),
            'roles' => Role::get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * For getting users through axios.
     *
     * @param Request $request
     *
     * @return string json_encode
     */
    public function getUsers(Request $request)
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_user, 'view')) {
            return abort(404);
        }

        $users = User::select($this->getPublicColumnByUser())
            ->orderBy('name', 'asc');

        if (! empty($request->q)) {
            $users->where('name', 'LIKE', '%' . $request->q . '%');
        }

        if ($this->access($me->role->acs_role, 'view')) {
            $users->addSelect('role_id')->with('role');

            if (! empty($request->role) && $request->role > 0) {
                $users->where('role_id', '=', (int) $request->role);
            }
        }

        if ($this->access($me->role->acs_job, 'view')) {
            $users->addSelect('job_id')->with('job');

            if (! empty($request->job) && $request->job > 0) {
                $users->where('job_id', '=', (int) $request->job);
            }
        }

        if (! empty($request->active)) {
            $users->where('active', '=', $request->active > 0);
        }

        if (! empty($request->del)) {
            $users->where('delete', '=', $request->del > 0);
        } elseif (! isset($request->del)) {
            $users->where('delete', '=', 0);
        }

        $count = in_array((int)$request->count, [10, 25, 50, 75, 100]) ? (int)$request->count : 25;

        return json_encode($users->paginate($count));
    }

    /**
     * Get data accessible to all users.
     *
     * @return array
     */
    public static function getPublicColumnByUser()
    {
        return ['id', 'name', 'nick', 'photo', 'active', 'delete',
            'email', 'work_email', 'phone', 'work_phone'];
    }
}
