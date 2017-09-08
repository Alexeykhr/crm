<?php

// Main page
Route::get('/', 'DashboardController@index');

// Authentication
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('login', 'Auth\LoginController@login');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

// Users
Route::resource('users', 'UserController');
Route::get('profile', 'UserController@profile');
Route::get('profile/edit', 'UserController@profileEdit');
Route::get('axios/users.get', 'UserController@getUsers');

// Roles
Route::resource('roles', 'RoleController');
Route::get('roles.get', 'RoleController@get');

// Jobs
Route::resource('jobs', 'JobController');
Route::get('jobs.get', 'JobController@get');
Route::get('jobs.exist', 'JobController@exist');
Route::post('jobs.transfer', 'JobController@transfer');

// Folders
Route::resource('folders', 'FolderController');

// Logs
Route::get('logs', 'LogController@index');
Route::get('axios/logs.get', 'LogController@getLogs');

// Calendar
Route::get('axios/calendar.get', 'CalendarController@getCalendar');
Route::get('calendar', 'CalendarController@index');
