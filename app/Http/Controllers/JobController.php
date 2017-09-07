<?php

namespace App\Http\Controllers;

use App\Job;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_job, 'view')) {
            return abort(404);
        }

        $jobs = Job::withCount('users')
            ->paginate(25);

        return view('jobs.index', [
            'me'        => $me,
            'jobs'      => $jobs,
            'canCreate' => $this->access($me->role->acs_job, 'create'),
            'canDelete' => $this->access($me->role->acs_job, 'delete'),
            'canEdit'   => $this->access($me->role->acs_job, 'edit'),
            'canTransfer' => $this->access($me->role->acs_job, 'edit')
                && $this->access($me->role->acs_user, 'edit')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_job, 'create')) {
            return abort(404);
        }

        return view('jobs.create', [
            'me' => $me,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_job, 'create')) {
            return abort(404);
        }

        $this->validate($request, [
            'title' => 'required|unique:jobs|min:3|max:60'
        ]);

        Job::insert([
            'title' => $request->title
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * [Ajax] Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_job, 'delete')) {
            return abort(404);
        }

        $job = Job::withCount('users')->where('id', '=', $id)->first();

        if (empty($job)) {
            return response()->json(['error' => ['validation.empty']], 422);
        }

        if ($job->users_count > 0) {
            return response()->json(['error' => ['validation.exists_users']], 422);
        }

        return Job::destroy($id);
    }

    /**
     * [Ajax] Getting users.
     *
     * @param Request $request
     *
     * @return string json_encode
     */
    public function get(Request $request)
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_job, 'view')) {
            return abort(404);
        }

        $jobs = Job::withCount('users');

        if (!empty($request->sortColumn) && !empty($request->sortType)) {
            if (in_array($request->sortType, ['asc', 'desc']) &&
                in_array($request->sortColumn, ['id', 'title', 'users_count'])) {
                $jobs->orderBy($request->sortColumn, $request->sortType);
            }
        }

        if (! empty($request->q)) {
            $jobs->where('title', 'LIKE', '%' . $request->q . '%');
        }

        $count = in_array((int)$request->count, [10, 25, 50, 75, 100]) ? (int)$request->count : 25;

        return json_encode($jobs->paginate($count));
    }

    /**
     * [Ajax] Checking user.
     *
     * @param Request $request
     *
     * @return boolean
     */
    public function exist(Request $request)
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_job, 'view')) {
            return abort(404);
        }

        return json_encode(Job::where('title', '=', $request->title)->exists());
    }

    /**
     * [Ajax] Transfer all users in other job.
     *
     * @param Request $request
     *
     * @return boolean
     */
    public function transfer(Request $request)
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_job, 'edit') &&
            ! $this->access($me->role->acs_user, 'edit')) {
            return abort(404);
        }

        $this->validate($request, [
            'from' => 'required|integer|min:1',
            'to'   => 'required'
        ]);

        if (is_numeric($request->to)) {
            return $this->transferById($request);
        }

        return $this->transferByTitle($request);
    }

    /**
     * [Ajax] Transfer all users in other job [ID].
     *
     * @param Request $request
     *
     * @see JobController::transfer()
     *
     * @return bool
     */
    public function transferById($request)
    {
        $this->validate($request, [
            'to' => 'integer|different:from|min:1|exists:jobs,id'
        ]);

        return User::where('job_id', '=', $request->from)
            ->update([
                'job_id' => $request->to
            ]);
    }

    /**
     * [Ajax] Transfer all users in other job [Title].
     *
     * @param Request $request
     *
     * @see JobController::transfer()
     *
     * @return boolean
     */
    public function transferByTitle($request)
    {
        $job = Job::where('title', '=', $request->to)->first();

        if (empty($job)) {
            return response()->json(['to' => ['validation.exists']], 422);
        }

        if ($job->id == $request->from) {
            return response()->json(['to' => ['validation.different']], 422);
        }

        return User::where('job_id', '=', $request->from)
            ->update([
                'job_id' => $job->id,
            ]);
    }
}
