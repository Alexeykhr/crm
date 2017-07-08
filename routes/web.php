<?php

Auth::routes();

Route::get('/', 'DashboardController@index');

Route::get('/users', 'UserController@index');
Route::get('/profile', 'UserController@profile');
Route::get('/user{id}', 'UserController@user');
Route::post('/user{id}', 'UserController@save');
