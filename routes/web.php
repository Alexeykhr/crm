<?php

Auth::routes();

Route::get('/', 'DashboardController@index');
Route::get('/me', 'UserController@profile');

Route::get('/u', 'UserController@index');
Route::get('/u/create', 'UserController@create');
Route::get('/u/{id}', 'UserController@user');
Route::post('/u/{id}', 'UserController@save');

Route::get('/c', 'CalendarController@index');
Route::get('/j', 'LogController@index');

// Axios
Route::get('/axios/u/get', 'UserController@getUsers');
Route::get('/axios/l/get', 'LogController@getLogs');
