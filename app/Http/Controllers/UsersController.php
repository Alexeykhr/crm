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

    public function active($id)
    {
        $id = (int)$id;

        if ($id === Auth::user()->id) {
            return response()->json('Неможливо себе видалити', 422);
        }

        $user = User::where('id', $id)->firstOrFail();

        $isUpdated = User::where('id', $id)->update([
            'is_active' => !$user->is_active,
        ]);

        if ($isUpdated) {
            return response()->json('Збережно');
        }

        return response()->json('Зміни не були збережені', 422);
    }
}
