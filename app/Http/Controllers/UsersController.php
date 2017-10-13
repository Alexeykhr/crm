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
            return response()->json('Неможливо себе заблокувати', 403);
        }

        $user = User::where('id', $id)->firstOrFail();

        $isUpdated = User::where('id', $id)->update([
            'is_active' => !$user->is_active,
        ]);

        if ($isUpdated) {
            return response()->json($user->is_active ? 'Заблоковано' : 'Розблоковано');
        }

        return response()->json('Зміни не були збережені', 422);
    }

    public function delete($id)
    {
        $id = (int)$id;

        if ($id === Auth::user()->id) {
            return response()->json('Неможливо себе видалити', 403);
        }

        $isDeleted = User::where('id', $id)->delete();

        if ($isDeleted) {
            return response()->json('Видалено');
        }

        return response()->json('Не видалено');
    }

    public function save($id)
    {
        // TODO: If user is_active!
    }
}
