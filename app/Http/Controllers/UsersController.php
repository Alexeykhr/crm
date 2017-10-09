<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function all(Request $request)
    {
        $this->validate($request, [
            'count' => 'required|integer|between:1,100'
        ]);

        $users = User::paginate($request->count);

        return response()->json($users);
    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1',
        ]);

        $user = User::where('id', $request->id)->firstOrFail();

        return response()->json($user);
    }

    public function block($id)
    {
        $id = (int)$id;

        if ($id === Auth::user()->id) {
            return response()->json(['error' => ['Видалити себе неможливо']], 422);
        }

        dd(Auth::user()->id);
        dd($id);
    }
}
