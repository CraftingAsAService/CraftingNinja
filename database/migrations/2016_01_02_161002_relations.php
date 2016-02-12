<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use DB;

class Relations extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// What a recipe could produce
		Schema::create('recipe_rewards', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('recipe_id')->unsigned(); // FK to recipes
			$table->integer('item_id')->unsigned(); // FK to items

			$table->smallInteger('yield')->unsigned(); // How many of this item the recipe produces
			$table->boolean('hq'); // Can the output be HQ?
			$table->tinyInteger('chance')->unsigned()->nullable(); // Store 1 - 100. Assume 100 at Null. Chance that this item is produced.

			$table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
		});

		// What a recipe requires to create
		Schema::create('recipe_requirements', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('recipe_id')->unsigned(); // FK to recipes
			$table->integer('item_id')->unsigned(); // FK to items

			$table->smallInteger('amount')->unsigned(); // How many of this item does it take
			$table->boolean('hq'); // Is the required version HQ?
			$table->boolean('used'); // Is the item removed from your inventory after creation?

			$table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
		});

		// Recipes can require specific stats
		Schema::create('recipe_stat', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('recipe_id')->unsigned(); // FK to recipes
			$table->integer('stat_id')->unsigned(); // FK to stats

			$table->smallInteger('amount')->unsigned(); // How much of the stat is required

			$table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
			$table->foreign('stat_id')->references('id')->on('stats')->onDelete('cascade');
		});

		// Jobs have a stat weight
		Schema::create('job_stat', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned(); // FK to jobs
			$table->integer('stat_id')->unsigned(); // FK to stats

			$table->tinyInteger('weight'); // How much of the stat is required, could be negative

			$table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
			$table->foreign('stat_id')->references('id')->on('stats')->onDelete('cascade');
		});

		// Items can have stats
		Schema::create('item_stat', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('item_id')->unsigned(); // FK to items
			$table->integer('stat_id')->unsigned(); // FK to stats

			$table->smallInteger('amount')->unsigned(); // How much of the stat is produced
			$table->tinyInteger('chance')->unsigned()->nullable(); // Store 1 - 100. Assume 100 at Null. Chance that this stat exists on the item
			$table->boolean('requires_set'); // Only produces stat if it's part of a set
			$table->smallInteger('limited')->unsigned()->nullable(); // How many seconds the "buff" lasts.  Assume Forever at Null.

			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('stat_id')->references('id')->on('stats')->onDelete('cascade');
		});

		// Quests have rewards
		Schema::create('quest_rewards', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('item_id')->unsigned(); // FK to items
			$table->integer('quest_id')->unsigned(); // FK to quests

			$table->smallInteger('amount')->unsigned(); // How much of the item is produced
			$table->boolean('choice'); // Is this item chosen from a list of available items?
			$table->tinyInteger('chance')->unsigned()->nullable(); // Store 1 - 100. Assume 100 at Null. Not every quest guarantees a reward

			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('quest_id')->references('id')->on('quests')->onDelete('cascade');
		});

		// Quests require items for turnin
		Schema::create('quest_requirements', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('item_id')->unsigned(); // FK to items
			$table->integer('quest_id')->unsigned(); // FK to quests

			$table->smallInteger('amount')->unsigned(); // How much of the item is required
			$table->boolean('hq'); // Is the required version HQ?
			$table->boolean('used'); // Is the item removed from your inventory after turnin?

			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('quest_id')->references('id')->on('quests')->onDelete('cascade');
		});

		// A node can be found in many locations
		Schema::create('location_node', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('node_id')->unsigned(); // FK to nodes
			$table->integer('location_id')->unsigned(); // FK to locations

			$table->decimal('x', 6, 2); // X Coordinate
			$table->decimal('y', 6, 2); // Y Coordinate
			$table->decimal('z', 6, 2)->nullable(); // Z Coordinate, Uncommon

			$table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
			$table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
		});

		// A node can have many rewards
		Schema::create('node_rewards', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('item_id')->unsigned(); // FK to items
			$table->integer('node_id')->unsigned(); // FK to nodes

			$table->smallInteger('amount')->unsigned(); // How much of the item is produced
			$table->boolean('choice'); // Is this item chosen from a list of available items?
			$table->tinyInteger('chance')->unsigned()->nullable(); // Store 1 - 100. Assume 100% at Null.

			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
		});

		// An NPC can drop things, or give them to you
		Schema::create('npc_rewards', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('item_id')->unsigned(); // FK to items
			$table->integer('npc_id')->unsigned(); // FK to npcs

			$table->smallInteger('amount')->unsigned(); // How much of the item is produced
			$table->tinyInteger('chance')->unsigned()->nullable(); // Store 1 - 100. Assume 100% at Null.

			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('npc_id')->references('id')->on('npcs')->onDelete('cascade');
		});

		// Items can be placed into an equipment slot, but more specifically, an entire category of items (or even multiple categories) will fit into an equipment slot
		Schema::create('equipment_slot_category', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('category_id')->unsigned(); // FK to categories
			$table->integer('equipment_slot_id')->unsigned(); // FK to equipment_slots

			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
			$table->foreign('equipment_slot_id')->references('id')->on('equipment_slots')->onDelete('cascade');
		});

		// Items can be purchased in a shop, identify's if the shop sells an item
		// How much an item costs is handled separately
		Schema::create('item_shop', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('item_id')->unsigned(); // FK to items
			$table->integer('shop_id')->unsigned(); // FK to shops

			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
		});

		// Items are traded for other items in shops
		Schema::create('item_trading', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('item_id')->unsigned(); // FK to items
			$table->integer('shop_id')->unsigned()->nullable(); // FK to shops, OPTIONAL, just to mark if it's a shop specific price, and not a generic price

			$table->enum('type', ['buy', 'sell']);
			// If I purchase `item_id`, what do I need to turn in? (typically currency, could be other 'token' items)
			// Buy: `item_id` == What the shop has, `traded_item_id` == What I have to trade in
			// If I sell `item_id`, what do I get in return? (typically currency, could be other 'token' items)
			// Sell: `item_id` == What I have, `traded_item_id` == What the shop has to give me for it
			$table->integer('traded_item_id')->unsigned(); // FK to items, How much of the new item you get
			$table->integer('amount')->unsigned(); // How much of the new item you get

			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
		});

		// NPCs can be found in a lot of locations
		// A shop can be found in a lot of locations, but NPCs run them
		Schema::create('location_npc', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('npc_id')->unsigned(); // FK to npcs
			$table->integer('location_id')->unsigned(); // FK to locations

			$table->decimal('x', 6, 2); // X Coordinate
			$table->decimal('y', 6, 2); // Y Coordinate
			$table->decimal('z', 6, 2)->nullable(); // Z Coordinate, Uncommon

			$table->foreign('npc_id')->references('id')->on('npcs')->onDelete('cascade');
			$table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
		});

		// Tags can be used on many things, so make it a polymorphic relationship
		Schema::create('taggables', function(Blueprint $table) {
			$table->increments('id');

			$table->integer('tag_id')->unsigned(); // FK to tags

			// Traditionally a $table->morphs('taggable') would be sufficient, but I wanted unsigned on the integer
			$table->integer('taggable_id')->unsigned(); // FK to the "anything" section
			$table->string('taggable_type');

			$table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
		});

		// Users can edit almost everything.  A polymorphic relationship is appropriate here as well
		Schema::create('revisions', function(Blueprint $table) {
			$table->increments('id');

			$table->integer('user_id')->unsigned(); // FK to users

			// Traditionally a $table->morphs('revision') would be sufficient, but I wanted unsigned on the integer
			$table->integer('revision_id')->unsigned(); // FK to the "anything" section
			$table->string('revision_type');

			// Store a diff of what changed, a user comment, and if they considered it a minor change
			$table->string('description')->nullable();
			$table->boolean('minor');
			$table->text('diff');

			$table->timestamps();
		});

		// Users' revisions need approved
		Schema::create('pending_revisions', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('revision_id')->unsigned(); // FK to revisions
			$table->integer('user_id')->unsigned(); // FK to users, the Approver's ID

			$table->boolean('approved')->nullable()->default(null); // t/f if approved, null if waiting

			$table->timestamps();

			$table->foreign('revision_id')->references('id')->on('revisions')->onDelete('cascade');
		});

		// Users can be banned/suspended.  Keep a record of that
		Schema::create('user_bans', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned(); // FK to users
			$table->integer('mod_id')->unsigned(); // FK to users, the Moderator's ID

			$table->dateTime('expires_at'); // Date/Time when the ban is up (use created_at as a starting point)
			$table->string('reason')->nullable();
			$table->tinyInteger('threat_level'); // Rate the level of heineousness by the user.

			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		// Users can have advanced access.  Use this as a history of it as well as what activates them.
		Schema::create('advanced_crafters', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned(); // FK to users

            $table->integer('amount');
            $table->string('stripe_transaction_id');

            $table->timestamp('valid_until');

			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		// Users can be moderators, potentially locked down to a single game
		Schema::create('moderators', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned(); // FK to users
			$table->integer('mod_id')->unsigned(); // FK to users, Who gave moderator access to this user_id

			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
			'recipe_rewards',
			'recipe_requirements',
			'recipe_stat',
			'job_stat',
			'item_stat',
			'quest_rewards',
			'quest_requirements',
			'location_node',
			'node_rewards',
			'npc_rewards',
			'equipment_slot_category',
			'item_shop',
			'item_trading',
			'location_npc',
			'taggables',
			'revisions',
			'pending_revisions',
			'user_bans',
			'advanced_crafters',
			'moderators',
		];

		// DB::statement(DB::raw('SET FOREIGN_KEY_CHECKS=0'));

		foreach (array_reverse($tables) as $table)
			Schema::dropIfExists($table);

		// DB::statement(DB::raw('SET FOREIGN_KEY_CHECKS=1'));
	}
}
