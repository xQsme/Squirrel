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
    $banner=true;
    return view('welcome', compact('banner'));
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/settings', 'UserController@settings')->name('settings');
Route::get('/activate', 'GoogleController@activate')->name('activate');
Route::post('/complete', 'GoogleController@complete')->name('complete');
Route::get('/deactivate', 'GoogleController@deactivate')->name('deactivate');