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

	Route::get('/', 'PortalController@index')->name('portal');

});

Route::/*domain('{game}.' . config('app.base_url'))->*/middleware('is-game')->group(function() {

	Route::get('/', 'GameController@index')->name('home');

	// Route::post('scrolls/{id}/add', 'ScrollController@addAllEntriesToSling');
	// Route::post('scrolls/{id}/vote', 'ScrollController@vote');
	// Route::post('scrolls/{id}/publish', 'ScrollController@publish');

	Route::get('compendium', 'CompendiumController@index')->name('compendium');
	Route::post('compendium', 'CompendiumController@index')->name('compendium.search');

	// Route::get('crafting', 'CraftingController@index');

	Route::get('sling', 'SlingController@index')->name('sling');

	// Route::post('report', 'ReportController@create');

	Route::middleware('auth')->group(function() {

		Route::get('scrolls/create', 'ScrollController@create')->name('scrolls.create');
		Route::post('scrolls', 'ScrollController@store')->name('scrolls.store');

	});

});
