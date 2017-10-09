<?php

Route::post('login', 'AuthController@authenticate');

Route::group(['middleware' => 'jwt.auth'], function () {

    Route::group(['prefix' => 'users'], function () {

        Route::get('get', 'UsersController@get');
        Route::get('all', 'UsersController@all');

//        TODO: isAdmin
        Route::post('{id}/active', 'UsersController@active');

    });

});
