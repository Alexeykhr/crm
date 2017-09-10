<?php

namespace App\Http\Controllers;

use App\Job;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    const MODULE_LOG = 'Користувачі';

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

        $users = User::select(self::getPublicColumnByUser());

        if ($this->access($me->role->acs_role, 'view')) {
            $users->addSelect('role_id')->with('role');
        }

        if ($this->access($me->role->acs_job, 'view')) {
            $users->addSelect('job_id')->with('job');
        }

        return view('users.index', [
            'me'        => $me,
            'users'     => $users->paginate(10),
            'canCreate' => $this->access($me->role->acs_user, 'create'),
            'canEdit'   => $this->access($me->role->acs_user, 'edit'),
            'canDelete' => $this->access($me->role->acs_user, 'delete'),
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

        if (! $this->access($me->role->acs_user, 'create') &&
            ! $this->access($me->role->acs_role, 'view')) {
            return abort(404);
        }

        return view('users.create', [
            'me'     => $me,
            'jobs'   => Job::get(),
            'roles'  => Role::get(),
            'action' => 'create',
        ]);
    }

    /**
     * [Ajax] Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_user, 'create') &&
            ! $this->access($me->role->acs_role, 'view')) {
            return abort(404);
        }

        // TODO: Continue..
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

        if ($me->id == $id) {
            return $this->profile();
        }

        if (! $this->access($me->role->acs_user, 'view')) {
            return abort(404);
        }

        $user = User::select(self::getPublicColumnByUser())
            ->where('id', '=', $id);

        if ($this->access($me->role->acs_role, 'view')) {
            $user->addSelect('role_id')->with('role');
        }

        if ($this->access($me->role->acs_job, 'view')) {
            $user->addSelect('job_id')->with('job');
        }

        $user = $user->firstOrFail();

        if ($user->id == 1) {
            return response()->json(['error' => ['validation.id1']], 422);
        }

        return view('users.view', [
            'me'      => $me,
            'user'    => $user,
            'action'  => 'view',
            'canEdit' => $this->access($me->role->acs_user, 'edit')
                && $user->role && $me->role->level >= $user->role->level,
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

        return view('users.view', [
            'me'      => $me,
            'canEdit' => $me->role->acs_profile,
            'action'  => 'view',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * [Ajax] Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        // TODO: id1 != change role_id
    }

    /**
     * [Ajax] Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return boolean
     */
    public function destroy($id)
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_user, 'delete')) {
            return abort(404);
        }

        if ($id == 1) {
            return response()->json(['error' => ['validation.id1']], 422);
        }

        $user = User::with('role')->where('id', '=', $id)->firstOrFail();

        if (empty($user)) {
            return response()->json(['error' => ['validation.empty']], 422);
        }

        if ($me->role->level < $user->role->level) {
            return response()->json(['error' => ['validation.level']], 422);
        }

        $isDeleted = User::destroy($id);

        if ($isDeleted) {
            LogController::logDelete(self::MODULE_LOG, '[' . $user->id . '] ' . $user->name);
        }

        return $isDeleted;
    }

    /**
     * [Ajax] For getting users.
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

        $users = User::select(self::getPublicColumnByUser());

        if (! empty($request->sortColumn) && ! empty($request->sortType)) {
            if (in_array($request->sortType, ['asc', 'desc']) &&
                in_array($request->sortColumn, ['id', 'name'])) {
                $users->orderBy($request->sortColumn, $request->sortType);
            }
        }

        if (! empty($request->qUser)) {
            if (is_numeric($request->qUser)) {
                $users->where('id', '=', $request->qUser);
            } else {
                $users->where('name', 'LIKE', '%' . $request->qUser . '%');
            }
        }

        if ($this->access($me->role->acs_role, 'view')) {
            $users->addSelect('role_id')->with('role');

            if (! empty($request->qRole)) {
                $users->whereHas('role', function ($q) use ($request) {
                    if (is_numeric($request->qRole)) {
                        $q->where('id', '=', $request->qRole);
                    } else {
                        $q->where('title', 'LIKE', '%' . $request->qRole . '%');
                    }
                });
            }
        }

        if ($this->access($me->role->acs_job, 'view')) {
            $users->addSelect('job_id')->with('job');

            if (! empty($request->qJob)) {
                $users->whereHas('job', function ($q) use ($request) {
                    if (is_numeric($request->qJob)) {
                        $q->where('id', '=', $request->qJob);
                    } else {
                        $q->where('title', 'LIKE', '%' . $request->qJob . '%');
                    }
                });
            }
        }

        if (! empty($request->active)) {
            $users->where('active', '=', $request->active > 0);
        }

        $count = in_array((int)$request->count, [10, 25, 50, 75, 100]) ? (int)$request->count : 10;

        return json_encode($users->paginate($count));
    }

    /**
     * Get data accessible to all users.
     *
     * @return array
     */
    public static function getPublicColumnByUser()
    {
        return ['id', 'name', 'nick', 'photo', 'active',
            'email', 'work_email', 'phone', 'work_phone'];
    }
}
