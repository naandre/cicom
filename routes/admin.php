<?php

/**Dashboard*/
Route::get('/','Backend\DashboardController@index')->name('dashboard.index');
/* Usuarios */
Route::resource('users','Backend\Users\UserController')->middleware('permission:consultar_usu|crear_usu');
/* Roles */
Route::resource('roles','Backend\Users\RoleController')->middleware('permission:roles');
/* Permisos */
Route::resource('permissions','Backend\Users\PermissionController')->middleware('permission:roles');
