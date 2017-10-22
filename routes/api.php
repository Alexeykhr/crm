<?php

Route::post('/login', 'AuthController@auth')->middleware('guest');
Route::get('/refresh-token', 'AuthController@refreshToken')->middleware('guest');

Route::group(['middleware' => 'jwt.auth'], function () {

    Route::group(['prefix' => 'users'], function () {

        Route::get('get', 'UsersController@get');
        Route::get('all', 'UsersController@all');

//        TODO: isAdmin
        Route::put('{id}/active', 'UsersController@active');
        Route::delete('{id}', 'UsersController@delete');

    });

});
