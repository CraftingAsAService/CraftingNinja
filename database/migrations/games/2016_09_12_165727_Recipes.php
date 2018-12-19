<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Recipes extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// A recipes name is usually the item it produces, but that's not always the case
		Schema::create('recipes', function (Blueprint $table) {
			$table->increments('id');

			// The item that's made
			$table->integer('item_id')->unsigned()->index(); // FK to items
			$table->integer('job_id')->unsigned()->nullable()->index(); // FK to jobs

			$table->smallInteger('level')->unsigned()->nullable()->index(); // The recipes level, not an ilvl or an item's natural level
			$table->tinyInteger('sublevel')->unsigned()->nullable()->index(); // The recipes sublevel (ala star level in ffxiv)

			$table->smallInteger('yield')->unsigned()->default(1); // How many of this item the recipe produces
			$table->tinyInteger('quality')->unsigned()->default(0); // Quality reward type.  Configurable per game (0 = Normal, 1 = HQ // 0  Normal, 1 = Silver Star, 2 = Gold Star)
			$table->tinyInteger('chance')->unsigned()->nullable(); // Store 1 - 100. Assume 100 at Null. Chance that this item is produced.

			// Recipes can have Details (durability, quality, progress, can hq or not)

			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
		});

		Schema::create('recipe_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('recipe_id')->unsigned(); // FK to recipes

			$table->string('locale')->index();

			$table->string('name');
			$table->text('description')->nullable();

			$table->unique(['recipe_id', 'locale']);
			$table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
		});

		Schema::create('recipe_ingredients', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('item_id')->unsigned()->index(); // FK to items
			$table->integer('recipe_id')->unsigned()->index(); // FK to recipes

			$table->smallInteger('quantity')->default(1)->unsigned(); // How many of this item the recipe produces

			$table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('recipe_translations');
		Schema::dropIfExists('recipe_ingredients');
		Schema::dropIfExists('recipes');
	}
}
