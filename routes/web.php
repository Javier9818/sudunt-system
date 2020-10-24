<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Route::get('/cron', function () {
//     Artisan::command('schedule:run >> /dev/null 2>&1');
// });

Route::post('/login', 'Auth\LoginController@authenticate')->name('login');
Route::post('/logout', function(){ Auth::logout(); return redirect('/users');})->name('logout');
Route::get('/login', function () {return view('auth.login');});

Route::get('/login-google', 'Auth\LoginController@login_google')->name('login-google');
Route::get('/return-google', 'Auth\LoginController@return_google')->name('return-google');
Route::get('/votacion-desde-google/{email}', function ($email) {
	$email = base64_decode($email);
	return $email;
});

Route::get('/users', 'UserController@index')->middleware('auth');
Route::get('/', 'UserController@index')->middleware('auth');



Route::get('/votacion/{token}', 'VoteController@validation')->name('votation');
Route::post('/vote', 'VoteController@store')->name('votation');


Route::get('/padron', 'PadronController@index')->middleware('auth');
Route::post('/padron', 'PadronController@store')->middleware('auth');
Route::get('/padron/{id}', 'PadronController@edit')->middleware('auth');
Route::put('/padron/{id}', 'PadronController@update')->middleware('auth');
Route::delete('/padron/{id}', 'PadronController@destroy')->middleware('auth');
Route::get('/nuevo-empadronado', 'PadronController@create')->middleware('auth');

Route::get('/formularios', 'VoteController@statistics');
Route::get('/form/{id}', 'FormController@edit');
Route::put('/form/{id}', 'FormController@update');

