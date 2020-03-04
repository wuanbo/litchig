<?php

Route::post('auth/login', 'Auth\LoginController@login');
Route::post('auth/logout', 'Auth\LoginController@logout');
