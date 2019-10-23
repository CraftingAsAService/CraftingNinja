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
		Schema::create('npc_shop', function (Blueprint $table) {
			// Build the basics of the pivot
			$table->pivot();
		});

		// Shops sell items
		Schema::create('item_shop', function (Blueprint $table) {
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
		Schema::dropIfExists('npc_shops');
		Schema::dropIfExists('shop_translations');
		Schema::dropIfExists('shops');
	}
}
