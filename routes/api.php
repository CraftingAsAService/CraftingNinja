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

Route::namespace('Api')->/*domain('{game}.' . config('app.base_url'))->*/middleware('is-game')->group(function() {

	Route::post('item', 'ItemController@index');
	Route::post('recipe', 'RecipeController@index');
	Route::post('scroll', 'ScrollController@index');

});
