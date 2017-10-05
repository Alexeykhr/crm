<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Authenticate an user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('nickname', 'password');

        $validator = Validator::make($credentials, [
            'nickname' => 'required|between:3,40',
            'password' => 'required|between:6,60'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => ['message' => $validator->errors()]], 422);
        }

        $token = JWTAuth::attempt($credentials);

        // TODO: Get all permissions for user

        if ($token) {
            return response()->json(['response' => [
                'message' => 'Ви успішно увійшли в систему',
                'token' => $token,
                'user' => Auth::user()
            ]]);
        } else {
            return response()->json(['error' => ['message' => 'Невірний логін або пароль.']], 401);
        }
    }

    /**
     * TODO: temporary..
     * Get the user by token.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(Request $request)
    {
        return response()->json(JWTAuth::parseToken()->toUser());
    }
}
