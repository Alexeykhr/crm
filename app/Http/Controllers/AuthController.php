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
    public function auth(Request $request)
    {
        $credentials = $request->only('login', 'password');

        $validator = Validator::make($credentials, [
            'login' => 'required|between:3,40',
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
                'user' => Auth::user(),
                'permissions' => Auth::user(),
            ]]);
        }

        return response()->json(['error' => ['message' => 'Невірний логін або пароль.']], 401);
    }

    /**
     * Refresh token to user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken()
    {
        $token = JWTAuth::getToken();

        if (! $token) {
            return response()->json('Токен не дійсний', 401);
        }

        return response()->json(['token' => JWTAuth::refresh($token)]);
    }
}
