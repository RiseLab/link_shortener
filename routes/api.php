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

Route::post('/v1/users', 'UserController@store');

Route::get('/v1/users/me', function (Request $request){

	return $request->user();

})->middleware('auth.basic');

Route::group(['prefix' => '/v1/users/me/shorten_urls', 'middleware' => 'auth.basic'], function () {

	Route::get('/', 'ShortlinkController@index');

	Route::post('/', 'ShortlinkController@store');

	Route::get('/{id}', 'ShortlinkController@show');

	Route::delete('/{id}', 'ShortlinkController@destroy');

	Route::get('/{id}/referers', 'ShortlinkController@reportReferers');

	Route::get('/{id}/{interval}', 'ShortlinkController@reportTimeGraph');

});

Route::get('/v1/shorten_urls/{hash}', 'ShortlinkController@click');

Route::fallback(function (){
	return response()->json(['error' => 'Resource not found.'], 404);
});
