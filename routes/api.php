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

Route::any('{path?}', 'MainController@index')->where("path", ".+");

//TagController routes
Route::post('storeTag/{id}', 'tagController@store');
Route::post('deleteTag/{id}', 'tagController@delete');
Route::post('removeTag/{id}', 'tagController@remove');
Route::post('suggestTag', 'tagController@suggest');

//UserController routes
Route::get('showUser/{id}', 'userController@show');
Route::post('deleteUser/{id}', 'userController@delete');
Route::post('banUser/{id}', 'userController@ban');
