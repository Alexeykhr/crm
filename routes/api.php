<?php

Route::post('login', 'AuthController@authenticate');

Route::group(['middleware' => 'jwt.auth'], function () {

    Route::get('me', 'AuthController@getUser');

});
