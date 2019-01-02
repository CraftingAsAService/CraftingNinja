<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Coordinates extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coordinates', function (Blueprint $table) {
			// Fields
			$table->increments('id'); // Even though it's a glorified pivot table, the polymorphic nature makes an ID easier to manage here; a combination primary key would be every single other value here
			$table->unsignedInteger('zone_id')->default(0); // FK Zones

			// Many to Many Polymorphic table
			$table->unsignedInteger('coordinate_id');
			$table->string('coordinate_type');

			// A lot of different things (NPCs, Shops, mining/gathering nodes, quests, etc, etc) can exist in a zone at a specific spot
			$table->string('x')->nullable(); // X Coordinate
			$table->string('y')->nullable(); // Y Coordinate
			$table->string('z')->nullable();
			$table->unsignedSmallInteger('radius')->nullable(); // If this dot embodies the idea of many coordinates surrounding this one

			// Indexes
			$table->index(['coordinate_type', 'coordinate_id'], 'c');
			$table->index('zone_id', 'z');
			$table->cascadeDeleteForeign('zones');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('coordinates');
	}
}
