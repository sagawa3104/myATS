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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(function () {

    //ログイン後ホーム
    Route::get('/home', 'HomeController@index')->name('home');

    //管理機能
    Route::prefix('admin')->namespace('Admin')->name('admin.')->group(function () {
        Route::resource('/user', 'UserController');
        Route::resource('/project', 'ProjectController');
    });

    //一般機能
    Route::prefix('user')->namespace('User')->name('user')->group(function () {
    });
});
