<?php

Route::post('login', 'AuthController@authenticate');

Route::group(['prefix' => 'v1', 'middleware' => 'jwt.auth'], function () {

    Route::post('logout', 'AuthController@logout');
    Route::get('me', 'AuthController@getUser');

});
