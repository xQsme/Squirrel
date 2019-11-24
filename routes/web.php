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
Route::get('/security', function () {
    $banner=true;
    return view('security', compact('banner'));
});

Auth::routes();

Route::get('/multi-factor', 'MultiFactorController@index')->name('multi-factor');

Route::get('/pin', 'MultiFactorController@authPin')->name('auth-pin');
Route::post('/validatePin', 'MultiFactorController@validatePin')->name('validatePin');
Route::get('/forgotPin', 'MultiFactorController@forgotPin')->name('forgot-pin');
Route::middleware('multi_factor_authentication')->get('/changePin', 'MultiFactorController@changePin')->name('change-pin');
Route::middleware('multi_factor_authentication')->post('/savePin', 'MultiFactorController@savePin')->name('save-pin');

Route::middleware('multi_factor_authentication')->get('/home', 'HomeController@index')->name('home');
Route::middleware('multi_factor_authentication')->get('/settings', 'UserController@settings')->name('settings');

Route::middleware('multi_factor_authentication')->get('/email-activate', 'EmailController@activate')->name('email-activate');
Route::middleware('multi_factor_authentication')->get('/email-send', 'EmailController@send')->name('email-send');
Route::middleware('multi_factor_authentication')->post('/email-complete', 'EmailController@complete')->name('email-complete');
Route::middleware('multi_factor_authentication')->get('/email-deactivate', 'EmailController@deactivate')->name('email-deactivate');
Route::get('/email', 'EmailController@email')->name('email');
Route::post('/email-authenticate', 'EmailController@authenticate')->name('email-authenticate');

Route::middleware('multi_factor_authentication')->get('/fido-activate', 'FIDOController@activate')->name('fido-activate');
Route::middleware('multi_factor_authentication')->get('/getCreateArgs', 'FIDOController@getCreateArgs')->name('getCreateArgs');
Route::middleware('multi_factor_authentication')->post('/processCreate', 'FIDOController@processCreate')->name('processCreate');
Route::middleware('multi_factor_authentication')->post('/processCreate', 'FIDOController@processCreate')->name('processCreate');
Route::get('/getGetArgs', 'FIDOController@getGetArgs')->name('fido-getGetArgs');
Route::post('/processGet', 'FIDOController@processGet')->name('fido-processGet');
Route::middleware('multi_factor_authentication')->get('/fido-deactivate', 'FIDOController@deactivate')->name('fido-deactivate');

Route::middleware('multi_factor_authentication')->get('/google-activate', 'GoogleController@activate')->name('google-activate');
Route::middleware('multi_factor_authentication')->post('/google-complete', 'GoogleController@complete')->name('google-complete');
Route::middleware('multi_factor_authentication')->get('/google-deactivate', 'GoogleController@deactivate')->name('google-deactivate');
Route::post('/google-authenticate', 'GoogleController@authenticate')->name('google-authenticate');

Route::middleware('multi_factor_authentication')->get('/sms-activate', 'SMSController@activate')->name('sms-activate');
Route::middleware('multi_factor_authentication')->get('/sms-deactivate', 'SMSController@deactivate')->name('sms-deactivate');

Route::middleware('multi_factor_authentication')->get('/authenticated', 'HomeController@authenticated')->name('authenticated');

Route::get('/mfa-authentication/{method}', 'MultiFactorController@getAuthenticationView')->name('get-auth-view');