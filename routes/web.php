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

	// Route::get('books', 'BookController@index');
	// Route::get('books/{id}', 'BookController@show');
	// Route::post('books', 'BookController@store');
	// Route::post('books/{id}/add', 'BookController@addAllEntriesToKnapsack');
	// Route::post('books/{id}/vote', 'BookController@vote');
	// Route::post('books/{id}/publish', 'BookController@publish');

	Route::get('compendium', 'CompendiumController@index');
	Route::post('compendium', 'CompendiumController@index');

	// Route::get('crafting', 'CraftingController@index');

	Route::get('knapsack', 'KnapsackController@index');
	// Route::post('knapsack', 'KnapsackController@addActiveEntry');
	// Route::put('knapsack', 'KnapsackController@updateActiveEntry');
	// Route::delete('knapsack', 'KnapsackController@removeActiveEntry');
	// Route::delete('knapsack/all', 'KnapsackController@removeAllActiveEntries');

	// Route::post('listing/{id}/publish', 'ListingController@publish');
	// Route::delete('listing/{id}', 'ListingController@delete');

	// Route::post('report', 'ReportController@create');

	Route::middleware('auth')->group(function() {

		Route::get('books/create', 'BookController@create');
		Route::post('books', 'BookController@store');

	});

});
