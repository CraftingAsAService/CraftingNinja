<?php

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

// Set the Locale
Route::get('locale/{locale}', 'LocaleController@set');

// Socialite - Third Party Logins
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

// Normal Auth Routes
Auth::routes();
// Replacement logout
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::domain(config('app.base_url'))->group(function() {

	Route::get('/', 'PortalController@index');

});

Route::domain('{game}.' . config('app.base_url'))->middleware('is-game')->group(function() {

	Route::get('/', 'GameController@index');

	Route::get('books', 'BooksController@index');
	Route::get('books/{id}', 'BooksController@show');
	Route::post('books/{id}/vote', 'BooksController@vote');
	Route::post('books/{id}/publish', 'BooksController@publish');

	Route::get('compendium', 'CompendiumController@index');

	Route::get('knapsack', 'KnapsackController@index');
	Route::post('knapsack', 'KnapsackController@addActiveEntry');
	Route::delete('knapsack', 'KnapsackController@removeActiveEntry');
	// Route::post('knapsack/publish', 'KnapsackController@publish');

});
