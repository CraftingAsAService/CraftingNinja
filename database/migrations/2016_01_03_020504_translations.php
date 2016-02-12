<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Translations extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Translations design credit: http://stackoverflow.com/a/929430/286467
		// I had also considered a Polymorphic translations setup, but this one made just a little bit more sense
		// I lose the general ability to search "everything", but there's little point in doing that.
		// You should know at least the first category of what you're searching for
		//  (i.e. who's confusing if "Potion" is an Item or Quest?)
		//
		// Part of the decision was size based.  Looking at game_translations, this was the diff:
		//  Per-Entity: 20 + (L+1) + (L+1) + (L+1)
		//  	L+1's are Name, Short Name and Description
		//  Polymorphic: (22 + L+1 + L+2) + (22 + L+1 + L+2) + (22 + L+1 + L+2)
		//  	[L+2 as it would need to be a "Text" field to handle all data lengths]
		// So, to store th same data, we're looking at 3 times the

		/**
		 * Base Language Table
		 */

		Schema::create('languages', function (Blueprint $table) {
			$table->increments('id');
			$table->char('code', 2)->unique();
		});

		/**
		 * Entity Specific Tables
		 */

		// Considered a default field, but decided against
		// is_default, BOOL Generally the US/English will be default, but this gives something to query against

		Schema::create('game_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('game_id')->unsigned(); // FK to games
			$table->string('name');
			$table->string('abbreviation');
			$table->string('description');

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

		Schema::create('category_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('category_id')->unsigned(); // FK to categories
			$table->string('name');
			$table->string('description');

			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

		Schema::create('tag_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('tag_id')->unsigned(); // FK to items
			$table->string('name');

			$table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

		Schema::create('item_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('item_id')->unsigned(); // FK to items
			$table->string('name');
			$table->string('description');

			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

		Schema::create('recipe_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('recipe_id')->unsigned(); // FK to recipes
			$table->string('name');
			$table->string('description');

			$table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

		Schema::create('stat_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('stat_id')->unsigned(); // FK to stats
			$table->string('name');
			$table->string('abbreviation');
			$table->string('description');

			$table->foreign('stat_id')->references('id')->on('stats')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

		Schema::create('job_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('job_id')->unsigned(); // FK to jobs
			$table->string('name');
			$table->string('abbreviation');
			$table->string('description');

			$table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

		Schema::create('quest_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('quest_id')->unsigned(); // FK to quests
			$table->string('name');
			$table->string('description');

			$table->foreign('quest_id')->references('id')->on('quests')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

		Schema::create('node_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('node_id')->unsigned(); // FK to nodes
			$table->string('name');
			$table->string('description');

			$table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

		Schema::create('location_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('location_id')->unsigned(); // FK to locations
			$table->string('name');
			$table->string('description');

			$table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

		Schema::create('npc_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('npc_id')->unsigned(); // FK to npcs
			$table->string('name');
			$table->string('description');

			$table->foreign('npc_id')->references('id')->on('npcs')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

		Schema::create('equipment_slot_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('equipment_slot_id')->unsigned(); // FK to equipment_slots
			$table->string('name');
			$table->string('description');

			$table->foreign('equipment_slot_id')->references('id')->on('equipment_slots')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

		Schema::create('shop_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned(); // FK to languages
			$table->integer('shop_id')->unsigned(); // FK to shops
			$table->string('name');
			$table->string('description');

			$table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$tables = [
			'languages',
			'game_translations',
			'category_translations',
			'tag_translations',
			'item_translations',
			'recipe_translations',
			'stat_translations',
			'job_translations',
			'quest_translations',
			'node_translations',
			'location_translations',
			'npc_translations',
			'equipment_slot_translations',
			'shop_translations',
		];

		// DB::statement(DB::raw('SET FOREIGN_KEY_CHECKS=0'));

		foreach (array_reverse($tables) as $table)
			Schema::dropIfExists($table);

		// DB::statement(DB::raw('SET FOREIGN_KEY_CHECKS=1'));
	}
}
