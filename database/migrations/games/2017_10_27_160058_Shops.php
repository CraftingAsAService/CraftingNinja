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
			$table->increments('id');
		});

		// NPCs own shops
		Schema::create('npc_shop', function (Blueprint $table) {
			$table->increments('id');

			// Shops are run by NPCs, but more than one NPC can run a store
			$table->integer('npc_id')->unsigned()->default(0)->index(); // FK NPCs
			$table->integer('shop_id')->unsigned()->default(0)->index(); // FK Shops

			// Shops can have Coordinates

			$table->foreign('npc_id')->references('id')->on('npcs')->onDelete('cascade');
			$table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
		});

		// Shops will have a name
		Schema::create('shop_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('shop_id')->unsigned(); // FK to items

			$table->string('locale')->index();

			$table->string('name');

			$table->unique(['shop_id', 'locale']);
			$table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
		});

		// Shops have items, but really only superficially
		// Any meaningful item data belongs to the NPC who sells the item, not the shop that sells the item
		Schema::create('item_shop', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('item_id')->unsigned()->default(0)->index(); // FK Items
			$table->integer('shop_id')->unsigned()->default(0)->index(); // FK Shops

			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('item_shop');
		Schema::dropIfExists('shop_translations');
		Schema::dropIfExists('npc_shop');
		Schema::dropIfExists('shops');
	}
}
