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


//認証チェックは全機能で統一して行う
Route::middleware('auth')->group(function () {

    //ログイン後ホーム
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/calender', 'HomeController@calender')->name('calender');

    //管理機能
    Route::prefix('admin')->namespace('Admin')->name('admin.')->group(function () {
        Route::resource('/user', 'UserController');
        Route::prefix('user/{user}')->name('user.')->group(function () {
            Route::get('/assignment', 'ProjectAssignmentController@userIndex')->name('assignment.index');
            Route::post('/assignment', 'ProjectAssignmentController@assignProject')->name('assignment.assign');
        });
        Route::resource('/project', 'ProjectController');
        Route::prefix('project/{project}')->name('project.')->group(function () {
            Route::get('/assignment', 'ProjectAssignmentController@projectIndex')->name('assignment.index');
            Route::post('/assignment', 'ProjectAssignmentController@assignUser')->name('assignment.assign');
        });
    });

    //一般機能
    Route::namespace('User')->name('user.')->group(function () {
        Route::resource('/{user}/workrecord', 'WorkRecordController');
    });
});
