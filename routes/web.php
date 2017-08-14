<?php

// Main page
Route::get('/', 'DashboardController@index');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Users
Route::get('profile', 'UserController@profile');
Route::get('profile/edit', 'UserController@profileEdit');
Route::resource('users', 'UserController');
Route::get('axios/users.get', 'UserController@getUsers');

// Roles
Route::resource('roles', 'RoleController');
Route::get('axios/roles.get', 'RoleController@getRoles');

// Jobs
Route::resource('jobs', 'JobController');
Route::get('axios/jobs.get', 'JobController@getJobs');

// Logs
Route::get('logs', 'LogController@index');
Route::get('axios/logs.get', 'LogController@getLogs');

// Calendar
Route::get('axios/calendar.get', 'CalendarController@getCalendar');
Route::get('calendar', 'CalendarController@index');
