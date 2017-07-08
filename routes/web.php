<?php

Auth::routes();

Route::get('/', 'DashboardController@index');

Route::get('/u', 'UserController@index');
Route::get('/u/add', 'UserController@create');
Route::get('/u/{id}', 'UserController@user');
Route::post('/u/{id}', 'UserController@save');
Route::get('/me', 'UserController@profile');
