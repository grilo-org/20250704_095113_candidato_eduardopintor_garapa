<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

// Route::prefix('admin')->group(function () {

    Route::prefix('curadores')->group(function () {
        Route::get('', 'CuratorsController@index')->name('curadores');
        Route::get('create', 'CuratorsController@create')->name('curadores.create');
        Route::get('edit/{id}', 'CuratorsController@edit')->name('curadores.edit');
        Route::post('store', 'CuratorsController@store')->name('curadores.store');
        Route::post('update/{id}', 'CuratorsController@update')->name('curadores.update');
        Route::get('destroy/{id}', 'CuratorsController@destroy')->name('curadores.destroy');
        Route::get('export', 'CuratorsController@export')->name('curadores.export');
    });

    Route::prefix('experiencias')->group(function () {
        Route::get('', 'EstablishmentsController@index')->name('experiencias');
        Route::get('create', 'EstablishmentsController@create')->name('experiencias.create');
        Route::get('edit/{id}', 'EstablishmentsController@edit')->name('experiencias.edit');
        Route::post('store', 'EstablishmentsController@store')->name('experiencias.store');
        Route::post('update/{id}', 'EstablishmentsController@update')->name('experiencias.update');
        Route::get('destroy/{id}', 'EstablishmentsController@destroy')->name('experiencias.destroy');
        Route::get('export', 'EstablishmentsController@export')->name('experiencias.export');
    });

    Route::prefix('comidas')->group(function () {
        Route::get('', 'FoodsController@index')->name('comidas');
        Route::get('create', 'FoodsController@create')->name('comidas.create');
        Route::get('edit/{id}', 'FoodsController@edit')->name('comidas.edit');
        Route::post('store', 'FoodsController@store')->name('comidas.store');
        Route::post('update/{id}', 'FoodsController@update')->name('comidas.update');
        Route::get('destroy/{id}', 'FoodsController@destroy')->name('comidas.destroy');
    });

    Route::prefix('usuarios')->group(function () {
        Route::get('', 'UsersController@index')->name('usuarios');
        Route::get('show/{id}', 'UsersController@show')->name('usuarios.show');
        Route::get('create', 'UsersController@create')->name('usuarios.create');
        Route::get('edit/{id}', 'UsersController@edit')->name('usuarios.edit');
        Route::post('store', 'UsersController@store')->name('usuarios.store');
        Route::post('update/{id}', 'UsersController@update')->name('usuarios.update');
        Route::get('destroy/{id}', 'UsersController@destroy')->name('usuarios.destroy');
        Route::get('export', 'UsersController@export')->name('usuarios.export');
    });

// });


Route::prefix('cidades')->group(function () {
    Route::get('/', 'LocationController@index')->name('cidades');
    Route::get('list/{state_is}', 'LocationController@list')->name('cidades.list');
});
