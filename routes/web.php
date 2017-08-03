<?php

// Main page
Route::get('/', 'DashboardController@index');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

// Users
Route::get('profile', 'UserController@profile');
Route::resource('users', 'UserController');

// Logs
Route::resource('logs', 'LogController');

// Calendar
Route::resource('calendar', 'CalendarController');

// Axios
Route::get('axios/users.get', 'UserController@getUsers');
Route::get('axios/logs.get', 'LogController@getLogs');
