<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NpcItems extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Shops have items
		Schema::create('item_npc', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('item_id')->unsigned()->default(0)->index(); // FK Items
			$table->integer('npc_id')->unsigned()->default(0)->index(); // FK NPCs

			// If sellable, it will have a price
			$table->integer('item_price_id')->unsigned()->nullable(); // FK ItemPrice
			// If dropped, it might have a rate, 0-100%, assume 100% at `null`
			$table->tinyInteger('rate')->unsigned()->nullable();

			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('npc_id')->references('id')->on('npcs')->onDelete('cascade');
		});

		// Splitting out item's price as it will generally be shared amongst a million npcs
		// e.g. A basic sword is sold in 100 stores for 10 gil; just eliminating data repetition
		Schema::create('item_price', function (Blueprint $table) {
			$table->increments('id');

			// This pricing entry is for which quality?
			$table->tinyInteger('quality')->unsigned()->default(0);

			// Mark if there's an "alternate" currency at work, Treat null as normal currency
			$table->boolean('alt_currency')->nullable();

			// Purchase Price, but before showing - are there any vendors?
			$table->integer('purchase_price')->unsigned()->nullable();
			// A vendor will buy it for this price - From a crafting perspective, this isn't _as_ important though
			$table->integer('sale_price')->unsigned()->nullable();
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
		Schema::dropIfExists('item_npc');
	}
}
