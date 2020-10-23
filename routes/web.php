<?php

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

Route::post('/login', 'Auth\LoginController@authenticate')->name('login');
Route::post('/logout', function(){ Auth::logout(); return redirect('/users');})->name('logout');
Route::get('/login', function () {return view('auth.login');});


Route::get('/users', 'UserController@index')->middleware('auth');
Route::get('/', 'UserController@index')->middleware('auth');



Route::get('/padron', 'PadronController@index')->middleware('auth');
Route::post('/padron', 'PadronController@store')->middleware('auth');
Route::get('/padron/{id}', 'PadronController@edit')->middleware('auth');
Route::put('/padron/{id}', 'PadronController@update')->middleware('auth');
Route::delete('/padron/{id}', 'PadronController@destroy')->middleware('auth');
Route::get('/nuevo-empadronado', 'PadronController@create')->middleware('auth');


Route::get('/home', 'HomeController@index')->name('home');
