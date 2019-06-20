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
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');


Route::get('/main', 'MainController@main');
Route::get('users', 'UsersController@get');
Route::get('users/{id}', 'UsersController@getOneUser');
Route::post('users', 'UsersController@regist');
Route::post('users/{id}/update', 'UsersController@update');
Route::delete('users/{id}', 'UsersController@delete');

Route::post('users/login', 'UsersController@login');
Route::get('users/login1', 'UsersController@login1')->name('login');

Route::post('users/test', 'UsersController@test');
Route::get('curl', 'UsersController@curl');



Route::group(['middleware' => 'auth:api'], function(){
    Route::get('users/details', 'UsersController@details');
    Route::get('download', 'UsersController@download');
});
