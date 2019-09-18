<?php

/**Dashboard*/
Route::get('/','Backend\DashboardController@index')->name('dashboard.index');

/* Usuarios */
Route::resource('users','Backend\Users\UserController');
/* Roles */
Route::resource('roles','Backend\Users\RoleController');
/* Permisos */
Route::resource('permissions','Backend\Users\PermissionController');
