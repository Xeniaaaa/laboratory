<?php

use Illuminate\Http\Request;

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

Route::get('/OnOff', 'Sensors@OnOff');
Route::get('/speed/{value}', 'Sensors@speed');
Route::get('/data', 'Sensors@get');

Route::get('auth/login', 'AuthController@getLogin');

Route::get('/admin/tables', 'AdminController@getTables');


Route::get('/admin/table/{name}', 'AdminController@getTable');
Route::post('/admin/addObject', 'AdminController@addNewObject');
Route::post('/admin/saveData', 'AdminController@saveObject');

// Route::post('auth/login', 'Auth\AuthController@postLogin');
// Route::get('auth/logout', 'Auth\AuthController@getLogout');
