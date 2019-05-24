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

Route::get('/multi-factor', 'MultiFactorController@index')->name('multi-factor');

Route::middleware('multi_factor_authentication')->get('/home', 'HomeController@index')->name('home');
Route::middleware('multi_factor_authentication')->get('/settings', 'UserController@settings')->name('settings');

Route::middleware('multi_factor_authentication')->get('/email-activate', 'EmailController@activate')->name('email-activate');
Route::middleware('multi_factor_authentication')->get('/email-confirm', 'EmailController@confirm')->name('email-confirm');
Route::middleware('multi_factor_authentication')->post('/email-complete', 'EmailController@complete')->name('email-complete');
Route::middleware('multi_factor_authentication')->get('/email-deactivate', 'EmailController@deactivate')->name('email-deactivate');

Route::middleware('multi_factor_authentication')->get('/fido-activate', 'FIDOController@activate')->name('fido-activate');
Route::middleware('multi_factor_authentication')->post('/fido-complete', 'FIDOController@complete')->name('fido-complete');
Route::middleware('multi_factor_authentication')->get('/fido-deactivate', 'FIDOController@deactivate')->name('fido-deactivate');

Route::middleware('multi_factor_authentication')->get('/google-activate', 'GoogleController@activate')->name('google-activate');
Route::middleware('multi_factor_authentication')->post('/google-complete', 'GoogleController@complete')->name('google-complete');
Route::middleware('multi_factor_authentication')->get('/google-deactivate', 'GoogleController@deactivate')->name('google-deactivate');

Route::middleware('multi_factor_authentication')->get('/sms-activate', 'SMSController@activate')->name('sms-activate');
Route::middleware('multi_factor_authentication')->get('/sms-deactivate', 'SMSController@deactivate')->name('sms-deactivate');

Route::middleware('multi_factor_authentication')->get('/authenticated', 'HomeController@authenticated')->name('authenticated');