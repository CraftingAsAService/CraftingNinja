<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Zones extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// A zone is a place you can go
		// Generally zones have coordinates
		Schema::create('zones', function (Blueprint $table) {
			// Fields
			$table->increments('id');
			$table->unsignedInteger('zone_id')->nullable(); // Self FK - Parent Zone

			// Indexes
			$table->index('zone_id');
			$table->cascadeDeleteForeign('zones');
		});

		Schema::create('zone_translations', function (Blueprint $table) {
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
		Schema::dropIfExists('zone_translations');
		Schema::dropIfExists('zones');
	}
}
