<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
	// Authentication stuff (login, register, etc)
    Route::auth();

    // Account Management
	Route::group(['prefix' => 'account', 'middleware' => 'auth'], function () {
		Route::get('/', 'AccountController@index');
		Route::get('edit', 'AccountController@edit');
		Route::put('update', 'AccountController@update');
		Route::post('checkout', 'AccountController@checkout');
	});

	// Administrative Functions
	Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
		Route::get('/', 'AdminController@index');
		Route::get('users', 'AdminController@users');
	});

	// Ajax Calls
	Route::get('flyouts/{panel}', 'FlyoutsController@loadPanel');

	// Basic Pages
	Route::get('/', 'PagesController@home');
	Route::get('privacy', 'PagesController@privacy');

	// Language Switcher
	Route::get('language/{lang}', 'LanguageController@switchLanguage');

	// Game Selector
	Route::get('games/{game}', 'GamesController@switchGame');
	Route::get('games', 'GamesController@index');

	Route::group(['prefix' => '{game_prefix}'], function () {
		Route::get('tools', 'GamesController@index');
	});

	// Game-centric features
	// Route::controllers([
	// 	'tools' => 'ToolsController',
	// 	'compendium' => 'CompendiumController',
	// 	'knapsack' => 'KnapsackController',
	// ]);

});
