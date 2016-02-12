<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Core extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		/**
		 * STRING COLUMN TYPES
		 *  I have a tendancy to unnecessarily give a length to a string column.
		 *  Size is calculated as L+1, so setting it to 10 or 100 doesn't matter
		 *   UNLESS max size is important, which in most cases it isn't
		 *
		 *  Just a personal note to keep myself in check.
		 */

		// Contains only language-neutral data.  game_translations available for name/etc
		Schema::create('games', function (Blueprint $table) {
			$table->increments('id');
			$table->string('slug'); // ffxiv, wow, sos, dqh, rf4 // not a translatable thing, will be used to identify the game via subdomain or subdirectory
			$table->string('version'); // 99.99.99
			$table->tinyInteger('currency_type')->unsigned(); // An identifier to distinguish how currency is.  Decimal'd?  Copper/Silver/Gold? Straight Gil/Yen system? In any event, all currency boils down to a single, non-decimal'd number.

			$table->index('slug');
		});

		// Contains only language-neutral data.  category_translations available for name/etc
		// Used for high level categorization of items, recipes, etc
		Schema::create('categories', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id')->unsigned(); // FK to games

			$table->tinyInteger('rank')->unsigned(); // Categories, for ease of viewing, can be ranked (Artisan always comes before Journeyman/etc)

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
		});

		// Contains only language-neutral data.  tag_translations available for name/etc
		// Just about anything can be tagged.  This will be a true polymorphic relationship
		Schema::create('tags', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id')->unsigned(); // FK to games

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
		});

		// Handled by Tags
		// 	- Booleans
		// 		- Uniqueness
		// 		- Tradeable
		// 		- Desynthable
		// 		- Projectable
		// 		- Crestworthy
		// 		- Repairable
		// 	- Rarity
		// 	- Requirements
		// 		- If a vendor or area is locked, a tag to explain that
		// 			- Requires Exhalted
		// 	- Cost "Feeling"
		// 		- If a user thinks an item is too Expensive, or is a really good deal
		// 			- Expensive
		// 			- Cheap

		// Contains only language-neutral data.  item_translations available for name/etc
		Schema::create('items', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id')->unsigned(); // FK to games
			$table->integer('category_id')->unsigned()->nullable(); // FK to categories

			$table->smallInteger('level')->unsigned()->nullable();
			$table->smallInteger('ilvl')->unsigned()->nullable();
			$table->boolean('is_equipment');
			$table->string('icon'); // Stores both a "t/f" (or maybe MD5) or another identifier for some kind of Font Icon

			$table->index('category_id');
			$table->index('is_equipment');

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
		});
		// Idea, but holding off on
		// TODO, document or something
		// identifier, VARCHAR A "maybe" column, used to tie it to an outside source.  Could use it's own table though.

		// TODO: How to handle Sockets?
		//   Equipment Mods?


		// Contains only language-neutral data.  recipe_translations available for name/etc
		// A recipes name is usually the item it produces, but that's not always the case
		Schema::create('recipes', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id')->unsigned(); // FK to games
			$table->integer('job_id')->unsigned(); // FK to jobs
			$table->integer('category_id')->unsigned()->nullable(); // FK to categories

			$table->smallInteger('level')->unsigned()->nullable(); // The recipes level, not an ilvl or an item's natural level

			$table->index(['job_id', 'level']);
			$table->index('category_id');

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
		});

		// Contains only language-neutral data.  stat_translations available for name/etc
		Schema::create('stats', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id')->unsigned(); // FK to games

			$table->boolean('primary'); // Is this a primary stat? (STR/DEX/etc)

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
		});

		// Contains only language-neutral data.  job_translations available for name/etc
		Schema::create('jobs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id')->unsigned(); // FK to games

			// Admittedly, this would do better as a SET datatype in MySQL, but Laravel doesn't recognize it in the Schema builder.
			// I could do some workarounds for that, but it's not worth it; this gets the job done
			$table->enum('type', ['battle', 'crafting', 'gathering', 'hero']); // The Hero type can do everything

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
		});

		// Contains only language-neutral data.  quest_translations available for name/etc
		Schema::create('quests', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id')->unsigned(); // FK to games
			$table->integer('job_id')->unsigned()->nullable(); // FK to jobs, optional

			$table->integer('start_npc_id')->unsigned()->nullable(); // FK NPCs, the starting NPC
			$table->integer('end_npc_id')->unsigned()->nullable(); // FK NPCs, the ending NPC.  If null, assume start NPC

			$table->boolean('unofficial')->nullable(); // If the quest isn't a "real" quest
			$table->boolean('repeatable'); // Can the quest be repeated?
			$table->boolean('multi'); // Is the quest a multi-turnin (3x)?  (Useful for FFXIV only possibly)

			$table->index('start_npc_id');
			$table->index('end_npc_id');
			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
		});


		// Contains only language-neutral data.  node_translations available for name/etc
		// This more-so defines a "node type" than an individual node.  location_node handles the individual node
		Schema::create('nodes', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id')->unsigned(); // FK to games

			$table->boolean('respawn'); // Does this node respawn?
			$table->boolean('restrictions'); // Does this node have specific restrictions? (to be detailed in the Description of the node)

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
		});

		// Contains only language-neutral data.  location_translations available for name/etc
		Schema::create('locations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id')->unsigned(); // FK to games

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
		});

		// Not every location has a map, so split it out
		// Maps belong to a location, so it's unnecessary to tie it to the game as well
		Schema::create('maps', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('location_id')->unsigned(); // FK to games

			$table->decimal('top_left_x', 6, 2); // Top-Left X Coordinate
			$table->decimal('top_left_y', 6, 2); // Top-Left Y Coordinate
			$table->decimal('bottom_right_x', 6, 2); // Bottom-Right X Coordinate
			$table->decimal('bottom_right_y', 6, 2); // Bottom-Right Y Coordinate

			$table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
		});

		// Contains only language-neutral data.  npc_translations available for name/etc
		Schema::create('npcs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id')->unsigned(); // FK to games

			$table->boolean('respawn'); // Does this npc respawn if killed?
			$table->boolean('restrictions'); // Does this node have specific restrictions? (to be detailed in the Description of the node)

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
		});

		// Contains only language-neutral data.  equipment_slot_translations available for name/etc
		Schema::create('equipment_slots', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id')->unsigned(); // FK to games

			$table->tinyInteger('rank')->unsigned(); // Equipment Slots are normally in some kind of ranking/order, for ease of viewing

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
		});

		// Contains only language-neutral data.  shop_translations available for name/etc
		Schema::create('shops', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('game_id')->unsigned(); // FK to games
			$table->integer('npc_id')->unsigned(); // FK to npcs

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
			$table->foreign('npc_id')->references('id')->on('npcs')->onDelete('cascade');
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
			'games',
			'categories',
			'tags',
			'items',
			'recipes',
			'stats',
			'jobs',
			'quests',
			'nodes',
			'locations',
			'maps',
			'npcs',
			'equipment_slots',
			'shops',
		];

		// DB::statement(DB::raw('SET FOREIGN_KEY_CHECKS=0'));

		foreach (array_reverse($tables) as $table)
			Schema::dropIfExists($table);

		// DB::statement(DB::raw('SET FOREIGN_KEY_CHECKS=1'));
	}
}
