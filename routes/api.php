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

Route::get('/v1/users/me', function (Request $request){

	return $request->user();

})->middleware('auth.basic');

Route::group(['prefix' => '/v1/users/me/shorten_urls', 'middleware' => 'auth.basic'], function () {

	Route::post('/', 'ShortlinkController@store');

	Route::get('/', 'ShortlinkController@index');

});
