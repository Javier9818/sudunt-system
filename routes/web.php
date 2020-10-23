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


Route::get('/users', function () { return view('admin.users.list');})->middleware('auth');

Route::get('/votacion/{token}', 'VoteController@validation')->name('votation');

Route::get('/home', 'HomeController@index')->name('home');
