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
		});

		Schema::create('npc_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('npc_translations');
		Schema::dropIfExists('npcs');
	}
}
