<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Items extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Contains only language-neutral data.  item_translations available for name/etc
		Schema::create('items', function (Blueprint $table) {
			// Fields
			$table->increments('id');

			// Items, by themselves are so varied in usage and statistics,
			// 	that we rely on Details and Attributes for their values.
			// 	Name and Description however are handled via the translations

			// General Item Attributes
			// "ilvl": 49, // Item Level - Items will generally always have some kind of level
			// "category": 10, // Category ID - Items will generally always be sorted into some kind of group
			// "icon": 588, // What picture to use, can be shared between items
			// Icons will be referenced as something like '/images/icons/items/$value.png'
			// Icons, if null, will instead use the ID of the row for simplicities sake
			// "rarity": 1, // Useful for coloring output, more than anything
			$table->unsignedInteger('category_id')->nullable(); // FK Categories
			$table->unsignedSmallInteger('ilvl')->default('1');
			$table->string('icon')->nullable();
			$table->unsignedTinyInteger('rarity')->default('0');

			// Ignoring these
			// "convertable": 1, // IGNORE, converting an item into materia outside scope, and a little too advanced to accurately account for
			// "tradeable": 1, // IGNORE, trading outside scope
			// "desynthSkill": 49, // IGNORE, desynthesis is a little too archaic and not particularly useful for the scope of the tool
			// "repair": 14, // IGNORE, repairing outside scope
			// "repair_item": 5598, // IGNORE, repairing outside scope
			// "glamour": 7664, // IGNORE, beauty only
			// "unlistable": 1, // IGNORE, auction house outside scope, usually goes hand in hand with untradable?
			// "attr_max": { ... }, // IGNORE, end game type stat, broader scope than I'll cover
			// "delivery": 242, // Not sure what this even is
			// "collectable": 1, // Not sure what this is either
			// "patch": 3.0, // IGNORE, scope
			// "patchCategory": 5, // IGNORE, scope

			// Indexes
			$table->cascadeDeleteForeign('categories');
		});

		Schema::create('item_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name');
			$table->text('description')->nullable();
		});
		// A simpler take on prices
		// While each NPC _could_ have differing prices for items, let's not and say we did.
		Schema::create('prices', function (Blueprint $table) {
			// Fields
			$table->increments('id');

			// This pricing entry is for which quality?
			$table->unsignedTinyInteger('quality')->default(0);

			// Mark if there's an "alternate" currency at work, identify it
			// Treat null as "normal" currency, though it may be an item proper as well
			$table->unsignedInteger('item_id')->nullable();

			// Purchase Price (Player purchasing from Vendor) == true, or Sell Price (Player selling to vendor) == false
			$table->boolean('market')->default(false);

			// The amount you're purchasing or selling for, in the lowest denominator ($20.56 stored as 2056)
			$table->unsignedInteger('amount')->default(0);

			// Indexes
			$table->cascadeDeleteForeign('items');
		});

		Schema::create('item_price', function (Blueprint $table) {
			// Build the basics of the pivot
			$table->pivot();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('item_price');
		Schema::dropIfExists('prices');
		Schema::dropIfExists('item_translations');
		Schema::dropIfExists('items');
	}
}
