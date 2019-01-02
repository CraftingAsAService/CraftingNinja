<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Games extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Contains only language-neutral data.  game_translations available for name/etc
		Schema::create('games', function (Blueprint $table) {
			// Fields
			$table->increments('id');
			$table->string('slug'); // ffxiv, wow, sos, dqh, rf4 // not a translatable thing, will be used to identify the game via subdomain or subdirectory
			$table->string('version')->nullable(); // 99.99.99
			// $table->tinyInteger('currency_type')->unsigned(); // An identifier to distinguish how currency is.  Decimal'd?  Copper/Silver/Gold? Straight Gil/Yen system? In any event, all currency boils down to a single, non-decimal'd number. // TODO, move this into a config file

			// Indexes
			$table->index('slug');
		});

		Schema::create('game_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name');
			$table->string('abbreviation');
			$table->string('description')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('game_translations');
		Schema::dropIfExists('games');
	}
}
