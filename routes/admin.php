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
/**Consulta información*/
Route::resource('search','Backend\Reports\ArticleReportController')->middleware('permission:dashboard');
/** Categorias **/
Route::resource('category','Backend\Articles\CategoryController')->middleware('permission:config');
/** Lineas de investigacion **/
Route::resource('line','Backend\Articles\LinesInvestigationController')->middleware('permission:config');
/** País **/
Route::resource('pais','Backend\Articles\PaisController')->middleware('permission:config');
/** Ciudad **/
Route::resource('ciudad','Backend\Articles\CiudadController')->middleware('permission:config');
/** Estensiones **/
Route::resource('extension','Backend\Articles\ExtensionController')->middleware('permission:config');
/** Imagenes **/
Route::resource('image','Backend\Articles\ImageController')->middleware('permission:config image');
/** Ultimo congreso */
Route::resource('lastcongress','Backend\Articles\LastCongressController')->middleware('permission:config image');
/** Articulo */
Route::resource('article','Backend\Articles\ArticleController')->middleware('permission:consulta_art');

/** Cargue masivo */
Route::resource('cargueMasivo','Backend\Articles\CargueMasivoController')->middleware('permission:consulta_art');
/** Autores */
Route::resource('author','Backend\Articles\AuthorController')->middleware('permission:cargar_arch');
Route::get('author\{articleId}\index','Backend\Articles\AuthorController@indexCustom')->name('author.index');
Route::get('author\{articleId}\create','Backend\Articles\AuthorController@createCustomer')->name('author.create');
Route::resource('detallecargue','Backend\Articles\DetalleCargueMasivoController')->middleware('permission:cargar_arch');
Route::get('detallecargue\{cargueMasivoId}\index','Backend\Articles\DetalleCargueMasivoController@indexDetail')->name('detallecargue.index');
Route::get('detallecargue\{cargueMasivoId}\create','Backend\Articles\DetalleCargueMasivoController@createDetalle')->name('detallecargue.create');
Route::get('cargueMasivo\{cargueMasivoId}\download','Backend\Articles\CargueMasivoController@download')->name('cargueMasivo.download');
Route::get('cargueMasivo\{cargueMasivoId}\descargarExcel','Backend\Articles\CargueMasivoController@descargarExcel')->name('cargueMasivo.descargarExcel');
Route::get('article/findCityFromCountry/{idCountry}', 'Backend\Articles\ArticleController@findCityFromCountry');