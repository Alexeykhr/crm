<?php

Auth::routes();

Route::get('/', 'DashboardController@index');

Route::get('/users', 'UserController@index');
