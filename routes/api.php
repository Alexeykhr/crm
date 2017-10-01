<?php

Route::post('login', 'AuthController@authenticate');
Route::post('logout', 'AuthController@logout');
Route::post('register', 'AuthController@register');
