<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Objectives extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Objectives are Quests, Fates, Leves, Achievements
		Schema::create('objectives', function (Blueprint $table) {
			$table->increments('id');

			// Is it a quest, fate, leve, achievement, etc?
			$table->tinyInteger('type')->nullable()->index();
			// Can you do this more than once?
			$table->binary('repeatable')->nullable();

			// Level requirement?
			$table->tinyInteger('level')->unsigned()->nullable()->index();
			// If restricted to a job group...
			$table->integer('group_id')->unsigned()->nullable()->index(); // FK JobSets Group IDs, not the ID of the table itself

			// NPC who gives quest
			$table->integer('issuer')->unsigned()->nullable(); // FK Npc
			// "levemete"
			$table->integer('target')->unsigned()->nullable(); // FK Npc

			// Objectives can have Coordinates
			// Objectives can have Details - plate, frame, areaicon, icon, etc etc
		});

		Schema::create('objective_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('objective_id')->unsigned(); // FK to objectives

			$table->string('locale')->index();

			$table->string('name');
			$table->string('description')->nullable();

			$table->unique(['objective_id', 'locale']);
			$table->foreign('objective_id')->references('id')->on('objectives')->onDelete('cascade');
		});


		Schema::create('objective_item_required', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('objective_id')->unsigned()->index(); // FK objectives

			// Which quality is required?
			$table->tinyInteger('quality')->unsigned()->default(0);

			// Item given/required
			$table->integer('item_id')->unsigned()->index();
			// Qty given/required
			$table->integer('quantity')->unsigned()->default(1);

			// treat experience points like an item
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('objective_id')->references('id')->on('objectives')->onDelete('cascade');
		});


		Schema::create('objective_item_reward', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('objective_id')->unsigned()->index(); // FK objectives

			// Which quality is given?
			$table->tinyInteger('quality')->unsigned()->default(0);

			// Item received
			$table->integer('item_id')->unsigned();
			// Amount received
			$table->integer('quantity')->unsigned()->default(1);
			// Drop Rate.  Null == 100% // 50 == 50%
			$table->tinyInteger('rate')->unsigned()->nullable();

			$table->foreign('objective_id')->references('id')->on('objectives')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('objective_item_reward');
		Schema::dropIfExists('objective_item_required');
		Schema::dropIfExists('objective_translations');
		Schema::dropIfExists('objectives');
	}
}
