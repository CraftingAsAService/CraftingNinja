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
		Schema::create('recipes', function (Blueprint $table) {
			// Fields
			$table->increments('id');
			// The item that's produced from the recipe
			$table->unsignedInteger('item_id'); // FK to items
			$table->unsignedInteger('job_id')->nullable(); // FK to jobs

			$table->unsignedSmallInteger('level')->nullable(); // The recipes level, not an ilvl or an item's natural level
			$table->unsignedTinyInteger('sublevel')->nullable(); // The recipes sublevel (ala star level in ffxiv)
			$table->unsignedSmallInteger('yield')->default(1); // How many of this item the recipe produces
			$table->unsignedTinyInteger('quality')->default(0); // Quality reward type.  Configurable per game (0 = Normal, 1 = HQ // 0  Normal, 1 = Silver Star, 2 = Gold Star)
			$table->unsignedTinyInteger('chance')->nullable(); // Store 1 - 100. Assume 100 at Null. Chance that this item is produced.

			// Recipes can have Details (durability, quality, progress, can hq or not)

			// Indexes
			$table->index('item_id', 'i');
			$table->index('job_id', 'j');
			$table->index('level', 'l');
			$table->index('sublevel', 'sl');
			$table->cascadeDeleteForeign('items');
			$table->cascadeDeleteForeign('jobs');
		});

		Schema::create('recipe_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name')->nullable(); // A recipes name is usually the item it produces, but that's not always the case
			$table->text('description')->nullable();
		});

		Schema::create('item_recipe', function (Blueprint $table) {
			// Build the basics of the pivot
			$table->pivot();

			// Additional Pivot Fields
			$table->unsignedSmallInteger('quantity')->default(1); // How many of this item the recipe produces

			// Description of table
			// Better known as Recipe Ingredients or Reagents
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('item_recipes');
		Schema::dropIfExists('recipe_translations');
		Schema::dropIfExists('recipes');
	}
}
