<?php

Auth::routes();

Route::get('/', 'DashboardController@index');

Route::get('/u', 'UserController@index');
Route::get('/u/create', 'UserController@create');
Route::get('/u/{id}', 'UserController@user');
Route::post('/u/{id}', 'UserController@save');
Route::get('/me', 'UserController@profile');

Route::get('/birthday', 'BirthdayController@index');

Route::get('/logs', 'LogController@index');

// ...
Route::get('/users/get', 'UserController@getUsers');
