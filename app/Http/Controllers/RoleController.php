<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_role, 'view')) {
            return abort(404);
        }

        $roles = Role::withCount('users')
            ->paginate(25);

        return view('roles.index', [
            'me'         => $me,
            'roles'      => $roles,
            'canCreate'  => $this->access($me->role->acs_role, 'create'),
            'canDelete'  => $this->access($me->role->acs_role, 'delete'),
            'canEdit'    => $this->access($me->role->acs_role, 'edit'),
            'canTransfer' => $this->access($me->role->acs_role, 'edit')
                && $this->access($me->role->acs_user, 'edit'),
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

        if (! $this->access($me->role->acs_role, 'create')) {
            return abort(404);
        }

        return view('roles.create', [
            'me' => $me,
        ]);
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
        $me = Auth::user();

        if (! $this->access($me->role->acs_role, 'edit')) {
            return abort(404);
        }

        $role = Role::where('id', '=', $id)->firstOrFail();

        if ($me->role->level < $role->level) {
            return response()->json(['error' => ['validation.level']], 422);
        }

//        TODO: create validation

        Role::where('id', '=', $id)->update([

        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_role, 'delete')) {
            return abort(404);
        }

        $role = Role::where($id, '=', $id)->firstOrFail();

        if ($me->role->level < $role->level) {
            return response()->json(['error' => ['validation.level']], 422);
        }

        return Role::destroy($id);
    }

    /**
     * For getting users through axios.
     *
     * @param Request $request
     *
     * @return string json_encode
     */
    public function get(Request $request)
    {
        $me = Auth::user();

        if (! $this->access($me->role->acs_role, 'view')) {
            return abort(404);
        }

        $roles = Role::withCount('users');

        if (!empty($request->sortColumn) && !empty($request->sortType)) {
            if (in_array($request->sortType, ['asc', 'desc']) &&
                in_array($request->sortColumn, ['id', 'title', 'level', 'users_count'])) {
                $roles->orderBy($request->sortColumn, $request->sortType);
            }
        }

        if (! empty($request->q)) {
            if (is_numeric($request->q)) {
                $roles->where('id', 'LIKE', '%'. $request->q . '%');
            } else {
                $roles->where('title', 'LIKE', '%' . $request->q . '%');
            }
        }

        $count = in_array((int)$request->count, [10, 25, 50, 75, 100]) ? (int)$request->count : 25;

        return json_encode($roles->paginate($count));
    }
}
