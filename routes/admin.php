<?php

/**Dashboard*/
Route::get('/','Backend\DashboardController@index')->name('dashboard.index');

/* Usuarios */
Route::resource('users','Backend\Users\UserController');
