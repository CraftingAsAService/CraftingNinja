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
			$table->increments('id');

			// NPCs table also encompases "mobs"
			$table->boolean('enemy')->default(0);
			$table->tinyInteger('level')->unsigned()->nullable();

			// NPCs can have Coordinates
		});

		Schema::create('npc_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('npc_id')->unsigned(); // FK to items

			$table->string('locale')->index();

			$table->string('name');

			$table->unique(['npc_id', 'locale']);
			$table->foreign('npc_id')->references('id')->on('npcs')->onDelete('cascade');
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
