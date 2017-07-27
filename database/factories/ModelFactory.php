<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

// $factory->define(App\Models\User::class, function (Faker\Generator $faker) {
// 	static $password;

// 	return [
// 		'name' => $faker->name,
// 		'email' => $faker->safeEmail,
// 		'password' => $password ?: $password = bcrypt('secret'),
// 		'remember_token' => str_random(10),
// 	];
// });

$factory->define(App\Models\Game::class, function(Faker\Generator $faker) {
	return [
		'slug' => $faker->word,
		'version' => $faker->latitude(),
		'name' => $faker->company,
		'abbreviation' => $faker->tld,
	];
});

$factory->define(App\Models\Game\Item::class, function(Faker\Generator $faker) {
	return [
		// 'has_quality' => 1,
		'level' => $faker->numberBetween(1, 999),
		'ilvl' => $faker->numberBetween(1, 999),
		// 'is_equipment' => 0,
		'name' => $faker->word,
		'description' => $faker->text,
	];
});

$factory->define(App\Models\Game\Recipe::class, function(Faker\Generator $faker) {
	return [
		'job_id' => 0, // Required, overwrite if it matters for the generated model
		'level' => $faker->numberBetween(1, 999),
	];
});

$factory->define(App\Models\Game\Job::class, function(Faker\Generator $faker) {
	return [
		'type' => 'Hero',
		'name' => $faker->word,
		'abbreviation' => $faker->tld,
	];
});