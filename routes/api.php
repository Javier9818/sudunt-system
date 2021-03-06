<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/empadronado/{code}', 'PadronController@obtenerEmpadronado');
Route::get('/data-simulacion/{formID}', 'VoteController@getDataSimulacion');
Route::get('/data-simulacion-reset/{formID}', 'VoteController@puestaCero');
Route::post('/voto-simulado', 'VoteController@setVotoSimulado');