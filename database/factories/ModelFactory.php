<?php

use App\Models\Game\Aspects\Category;
use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Book;
use App\Models\User;

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

$factory->define(User::class, function (Faker\Generator $faker) {
	return [
		'name' => $faker->name,
		'email' => $faker->email,
		'password' => bcrypt(str_random(10)),
		'remember_token' => str_random(10),
	];
});

$factory->define(Item::class, function(Faker\Generator $faker) {
	return [
		'name' => $faker->name,
	];
});

$factory->define(Category::class, function(Faker\Generator $faker) {
	return [
		'name' => $faker->name,
	];
});

$factory->define(Book::class, function(Faker\Generator $faker) {

	$bookOwner = factory(User::class)->create();

	return [
		'title' => 'Super Awesome Book',
		'user_id' => $bookOwner->id,
		'locale' => app()->getLocale()
	];
});
