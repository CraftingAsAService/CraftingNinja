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
			$table->increments('id');

			$table->smallInteger('level')->unsigned()->nullable();
			$table->tinyInteger('type')->unsigned()->nullable();

			// Nodes can have Coordinates
			// Nodes can have Details
		});

		Schema::create('node_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('node_id')->unsigned(); // FK to nodes

			$table->string('locale')->index();

			$table->string('name');

			$table->unique(['node_id', 'locale']);
			$table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
		});

		Schema::create('node_rewards', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('node_id')->unsigned()->index(); // FK to nodes
			$table->integer('item_id')->unsigned()->index(); // FK to items

			$table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
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
		Schema::dropIfExists('node_translations');
		Schema::dropIfExists('nodes');
	}
}
