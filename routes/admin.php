<?php

/**Dashboard*/
Route::get('/','Backend\DashboardController@index')->name('dashboard.index');
/************************* Modulo de usuarios *************************/
/* Usuarios */
Route::resource('users','Backend\Users\UserController')->middleware('permission:consultar_usu|crear_usu');
/* Roles */
Route::resource('roles','Backend\Users\RoleController')->middleware('permission:roles');
/* Permisos */
Route::resource('permissions','Backend\Users\PermissionController')->middleware('permission:roles');
/************************* Modulo de configuracion Articulos *************************/
/** Categorias **/
Route::resource('category','Backend\Articles\CategoryController')->middleware('permission:config');
/** Lineas de investigacion **/
Route::resource('line','Backend\Articles\LinesInvestigationController')->middleware('permission:config');
