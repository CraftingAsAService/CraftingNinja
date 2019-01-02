<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Shops extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Shops Exist
		Schema::create('shops', function (Blueprint $table) {
			// Fields
			$table->increments('id');
		});

		// Shops will have a name
		Schema::create('shop_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name');
		});

		// NPCs own shops
		Schema::create('npc_shops', function (Blueprint $table) {
			// Build the basics of the pivot
			$table->pivot();
		});

		// Splitting out item's price as it will generally be shared amongst a million npcs
		// e.g. A basic sword is sold in 100 stores for 10 gil; just eliminating data repetition
		Schema::create('prices', function (Blueprint $table) {
			// Fields
			$table->increments('id');

			// This pricing entry is for which quality?
			$table->unsignedTinyInteger('quality')->default(0);

			// Mark if there's an "alternate" currency at work, Treat null as normal currency
			$table->boolean('alt_currency')->nullable();

			// Purchase Price, but before showing - are there any vendors?
			$table->unsignedInteger('purchase_price')->nullable();
			// A vendor will buy it for this price - From a crafting perspective, this isn't _as_ important though
			$table->unsignedInteger('sell_price')->nullable();
		});

		// Shops have items
		Schema::create('item_npcs', function (Blueprint $table) {
			// Build the basics of the pivot
			$table->pivot();

			// Additional Pivot Fields
			// If sellable, it will have a price
			$table->unsignedInteger('price_id')->nullable(); // FK ItemPrice
			// If dropped, it might have a rate, 0-100%, assume 100% at `null`
			$table->unsignedTinyInteger('rate')->nullable();

			// Indexes
			$table->cascadeDeleteForeign('prices');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('item_npcs');
		Schema::dropIfExists('prices');
		Schema::dropIfExists('npc_shops');
		Schema::dropIfExists('shop_translations');
		Schema::dropIfExists('shops');
	}
}
