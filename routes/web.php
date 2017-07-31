<?php

Auth::routes();

Route::get('/', 'DashboardController@index');
Route::get('/me', 'UserController@profile');

Route::get('/users', 'UserController@index');
Route::get('/u/create', 'UserController@create');
Route::get('/u/{id}', 'UserController@user');
Route::post('/u/{id}', 'UserController@save');

Route::get('/calendar', 'CalendarController@index');
Route::get('/logs', 'LogController@index');

// Axios
Route::get('/axios/users.get', 'UserController@getUsers');
Route::get('/axios/logs.get', 'LogController@getLogs');
