<?php

use App\Models\Game;
use App\Models\Game\Aspects\Category;
use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Npc;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Equipment;
use App\Models\Game\Concepts\Scroll;
use App\Models\Game\Concepts\Niche;
use App\Models\User;
use Faker\Generator as Faker;

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

$factory->define(User::class, function (Faker $faker) {
	return [
		'name' => $faker->name,
		'email' => $faker->unique()->safeEmail,
		'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
		'remember_token' => str_random(10),
	];
});

$factory->define(Game::class, function(Faker $faker) {
	return [
		'slug' => $faker->userName,
		'version' => $faker->randomNumber(),
	];
});

$factory->define(Item::class, function(Faker $faker) {
	return [
		'name' => $faker->name,
	];
});

$factory->define(Category::class, function(Faker $faker) {
	return [
		'name' => $faker->name,
	];
});

$factory->define(Scroll::class, function(Faker $faker) {
	return [
		'user_id' => function () {
			return factory(User::class)->create()->id;
		},
	];
});

$factory->define(Recipe::class, function(Faker $faker) {
	return [

	];
});

$factory->define(Job::class, function(Faker $faker) {
	return [

	];
});

$factory->define(Niche::class, function(Faker $faker) {
	return [

	];
});

$factory->define(Npc::class, function(Faker $faker) {
	return [

	];
});

$factory->define(Equipment::class, function(Faker $faker) {
	return [

	];
});

$factory->define(Node::class, function(Faker $faker) {
	return [

	];
});

$factory->define(Objective::class, function(Faker $faker) {
	return [

	];
});
