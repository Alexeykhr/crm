<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
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
            return response()->json(['response' => ['token' => $token]]);
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
        JWTAuth::setToken($request->input('token'));
        $user = JWTAuth::toUser();
        return response()->json([$user]);
    }
}
