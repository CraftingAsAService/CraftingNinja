<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Nodes extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nodes', function (Blueprint $table) {
			// Fields
			$table->increments('id');

			$table->unsignedSmallInteger('level')->nullable();
			$table->unsignedTinyInteger('type')->nullable();

			// Nodes can have Coordinates
			// Nodes can have Details
		});

		Schema::create('node_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name');
		});

		Schema::create('item_nodes', function (Blueprint $table) {
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
		Schema::dropIfExists('item_nodes');
		Schema::dropIfExists('node_translations');
		Schema::dropIfExists('nodes');
	}
}
