<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NPCs extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('npcs', function (Blueprint $table) {
			// Fields
			$table->increments('id');

			// NPCs table also encompases "mobs"
			$table->boolean('enemy')->default(0);
			$table->unsignedSmallInteger('level')->nullable();

			// NPCs can have Coordinates
			// NPCs can have Details
		});

		Schema::create('npc_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name');
		});

		// NPCs or Mobs can drop items
		Schema::create('item_npc', function (Blueprint $table) {
			// Build the basics of the pivot
			$table->pivot();

			// Additional Pivot Fields
			// If dropped, it might have a rate, 0-100%, assume 100% at `null`
			$table->unsignedTinyInteger('rate')->nullable();
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
		Schema::dropIfExists('npc_translations');
		Schema::dropIfExists('npcs');
	}

}
