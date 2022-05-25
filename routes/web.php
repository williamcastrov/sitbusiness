<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
    phpinfo(); //return view('welcome');
});

/******************************************* */
// ACCIONES DE MERCADO RESPUESTO
////////////////////////////////////////////////
// Web Services WC.AAL-ERP
Route::post('mrp/api/{accion}', 'App\Http\Controllers\MrpServicesController@mrpGeneral');
Route::post('mrp/api/{accion}/', 'App\Http\Controllers\MrpServicesController@mrpGeneral');
Route::post('mrp/api/{accion}/{parametro}', 'App\Http\Controllers\MrpServicesController@mrpGeneral');
Route::get('mrp/api/{accion}', 'App\Http\Controllers\MrpServicesController@mrpGeneral');
Route::get('mrp/api/{accion}/', 'App\Http\Controllers\MrpServicesController@mrpGeneral');
Route::get('mrp/api/{accion}/{parametro}', 'App\Http\Controllers\MrpServicesController@mrpGeneral');

////////////////////////////////////////////////

/******************************************* */
// ACCIONES DE CYCLE WEAR
////////////////////////////////////////////////
// Web Services CYCLE WEAR
Route::post('cyclewear/api/{accion}', 'App\Http\Controllers\cyclewearController@cwrGeneral');
Route::post('cyclewear/api/{accion}/{parametro}', 'App\Http\Controllers\cyclewearController@cwrGeneral');
Route::get('cyclewear/api/{accion}', 'App\Http\Controllers\cyclewearController@cwrGeneral');
Route::get('cyclewear/api/{accion}/{parametro}', 'App\Http\Controllers\cyclewearController@cwrGeneral');

////////////////////////////////////////////////

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
