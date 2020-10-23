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
Route::post('/logout', function(){ Auth::logout(); return redirect('/');})->name('logout');
Route::get('/login', function () {return view('auth.login');});

Route::get('/login-google', 'Auth\LoginController@login_google')->name('login-google');
Route::get('/return-google', 'Auth\LoginController@return_google')->name('return-google');
Route::get('/votacion-desde-google/{email}', function ($email) {
	$email = base64_decode($email);
	return $email;
});

Route::get('/', function () { return view('admin.index');})->middleware('auth');




Route::get('/home', 'HomeController@index')->name('home');
