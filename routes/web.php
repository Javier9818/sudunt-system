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
Route::post('/logout', function(){ Auth::logout(); return redirect('/login');})->name('logout');
Route::get('/login', function () {return view('auth.login');});


Route::get('/sufragio-sudunt', function(){ return view('admin.vote.autentication');});
Route::get('/login-google', 'Auth\LoginController@login_google')->name('login-google');
Route::get('/return-google', 'Auth\LoginController@return_google')->name('return-google');



// Route::get('/users', 'UserController@index')->middleware('auth');

Route::get('/', 'UserController@index')->middleware('auth');



Route::get('/votacion/{token}', 'VoteController@validation')->name('votation');
Route::post('/vote', 'VoteController@store')->name('votation');

Route::resource('user', 'UserController')->middleware('can:rol-admin');
Route::resource('padron', 'PadronController')->middleware('can:rol-admin');
Route::resource('form', 'FormController')->middleware('auth');
Route::get('/form-statistics/{id}', 'VoteController@statistics')->middleware('auth');

// Route::get('/form/{id}', 'FormController@edit')->middleware('auth');


