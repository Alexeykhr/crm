<?php

Auth::routes();

Route::get('/', 'DashboardController@index');

Route::get('/users', 'UserController@index');
Route::get('/user{id}', 'UserController@user');
Route::get('/profile', 'UserController@profile');
