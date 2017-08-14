<?php

namespace App\Http\Controllers;

use App\Job;
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

        $jobs = Job::withCount(['users' => function($q) {
            $q->where('delete', '=', 0);
        }])
            ->where('delete', '=', 0)
            ->paginate(25);

        return view('jobs.index', [
            'me'        => $me,
            'jobs'      => $jobs,
            'canCreate' => $this->access($me->role->acs_job, 'create'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
    public function getJobs(Request $request)
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_job, 'view')) {
            return abort(404);
        }

        $jobs = Job::withCount(['users' => function($q) {
            $q->where('delete', '=', 0);
        }]);

        if (! empty($request->sortColumn) && ! empty($request->sortType)) {
            if (in_array($request->sortType, ['asc', 'desc']) &&
                in_array($request->sortColumn, ['title', 'users_count'])) {
                $jobs->orderBy($request->sortColumn, $request->sortType);
            }
        }

        if (! empty($request->q)) {
            $jobs->where('title', 'LIKE', '%' . $request->q . '%');
        }

        if (! empty($request->active)) {
            $jobs->where('active', '=', $request->active > 0);
        }

        if (! empty($request->del)) {
            $jobs->where('delete', '=', $request->del > 0);
        } elseif (! isset($request->del)) {
            $jobs->where('delete', '=', 0);
        }

        $count = in_array((int)$request->count, [10, 25, 50, 75, 100]) ? (int)$request->count : 25;

        return json_encode($jobs->paginate($count));
    }
}
