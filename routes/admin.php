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
/** Estensiones **/
Route::resource('extension','Backend\Articles\ExtensionController')->middleware('permission:config');
/** Imagenes **/
Route::resource('image','Backend\Articles\ImageController')->middleware('permission:config image');
/** Ultimo congreso */
Route::resource('lastcongress','Backend\Articles\LastCongressController')->middleware('permission:config image');
/** Articulo */
Route::resource('article','Backend\Articles\ArticleController')->middleware('permission:consulta_art');
/** Autores */
Route::resource('author','Backend\Articles\AuthorController')->middleware('permission:cargar_arch');
Route::get('author\{articleId}\index','Backend\Articles\AuthorController@indexCustom')->name('author.index');
Route::get('author\{articleId}\create','Backend\Articles\AuthorController@createCustomer')->name('author.create');
