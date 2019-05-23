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

Route::get('/email-activate', 'EmailController@activate')->name('email-activate');
Route::get('/email-confirm', 'EmailController@confirm')->name('email-confirm');
Route::post('/email-complete', 'EmailController@complete')->name('email-complete');
Route::get('/email-deactivate', 'EmailController@deactivate')->name('email-deactivate');

Route::get('/fido-activate', 'FIDOController@activate')->name('fido-activate');
Route::post('/fido-complete', 'FIDOController@complete')->name('fido-complete');
Route::get('/fido-deactivate', 'FIDOController@deactivate')->name('fido-deactivate');

Route::get('/google-activate', 'GoogleController@activate')->name('google-activate');
Route::post('/google-complete', 'GoogleController@complete')->name('google-complete');
Route::get('/google-deactivate', 'GoogleController@deactivate')->name('google-deactivate');

Route::get('/sms-activate', 'SMSController@activate')->name('sms-activate');
Route::post('/sms-complete', 'SMSController@complete')->name('sms-complete');
Route::get('/sms-deactivate', 'SMSController@deactivate')->name('sms-deactivate');