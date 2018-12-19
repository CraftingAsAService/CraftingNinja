<?php

$connections = [
	'testing' => [
		'driver'   => 'sqlite',
		'database' => ':memory:',
		'prefix'   => '',
	],
	'caas' => [
		'driver' => 'mysql',
		'host' => env('DB_HOST', 'localhost'),
		// Unit Testing on Mac uses port 33060
		'port' => env('DB_PORT', '3306'),
		'database' => env('DB_DATABASE', 'caas'),
		'username' => env('DB_USERNAME', ''),
		'password' => env('DB_PASSWORD', ''),
		'charset' => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix' => '',
		'strict' => false,
		'engine' => null,
	],
];

// if (env('APP_ENV') == 'testing')
// {
// 	$connections['caas'] = $connections['testing'];
// 	foreach (explode(',', env('VALID_GAMES')) as $gameSlug)
// 		$connections[$gameSlug] = array_merge($connections['caas'], ['prefix' => $gameSlug . '_']);
// }
// else
	foreach (explode(',', env('VALID_GAMES')) as $gameSlug)
		$connections[$gameSlug] = array_merge($connections['caas'], ['database' => 'caas_' . $gameSlug]);

// unset($defaultConfig);

return [

	/*
	|--------------------------------------------------------------------------
	| Default Database Connection Name
	|--------------------------------------------------------------------------
	|
	| Here you may specify which of the database connections below you wish
	| to use as your default connection for all database work. Of course
	| you may use many connections at once using the Database library.
	|
	*/

	'default' => env('DB_CONNECTION', 'mysql'),

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| Here are each of the database connections setup for your application.
	| Of course, examples of configuring each database platform that is
	| supported by Laravel is shown below to make development simple.
	|
	|
	| All database work in Laravel is done through the PHP PDO facilities
	| so make sure you have the driver for your particular database of
	| choice installed on your machine before you begin development.
	|
	*/

	'connections' => $connections,

	/*
	|--------------------------------------------------------------------------
	| Migration Repository Table
	|--------------------------------------------------------------------------
	|
	| This table keeps track of all the migrations that have already run for
	| your application. Using this information, we can determine which of
	| the migrations on disk haven't actually been run in the database.
	|
	*/

	'migrations' => 'migrations',

	/*
	|--------------------------------------------------------------------------
	| Redis Databases
	|--------------------------------------------------------------------------
	|
	| Redis is an open source, fast, and advanced key-value store that also
	| provides a richer set of commands than a typical key-value systems
	| such as APC or Memcached. Laravel makes it easy to dig right in.
	|
	*/

	'redis' => [

		'client' => 'predis',

		'default' => [
			'host' => env('REDIS_HOST', '127.0.0.1'),
			'password' => env('REDIS_PASSWORD', null),
			'port' => env('REDIS_PORT', 6379),
			'database' => env('REDIS_DB', 0),
		],

		'session' => [
			'host' => env('REDIS_HOST', '127.0.0.1'),
			'password' => env('REDIS_PASSWORD', null),
			'port' => env('REDIS_PORT', 6379),
			'database' => env('SESSION_DB', 1),
		],

	],

];
