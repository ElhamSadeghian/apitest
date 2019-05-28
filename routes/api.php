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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

header('Content-Type: application/json');

Route::get('/main', 'MainController@main');
Route::get('user/get', 'UsersController@get');
Route::post('user/regist', 'UsersController@regist');
Route::get('user/edit/{id}', 'UsersController@edit');
Route::post('user/update/{id}', 'UsersController@update');
Route::get('user/delete/{id}', 'UsersController@delete');
