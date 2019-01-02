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
			// Fields
			$table->increments('id');
			// If restricted to a job group...
			$table->unsignedInteger('niche_id')->nullable(); // FK JobSets Group IDs, not the ID of the table itself
			// NPC who gives quest
			$table->unsignedInteger('issuer_id')->nullable(); // FK Npc
			// "levemete"
			$table->unsignedInteger('target_id')->nullable(); // FK Npc

			// Is it a quest, fate, leve, achievement, etc?
			$table->tinyInteger('type')->nullable();
			// Can you do this more than once?
			$table->binary('repeatable')->nullable();
			// Level requirement?
			$table->unsignedTinyInteger('level')->nullable();

			// Objectives can have Coordinates
			// Objectives can have Details - plate, frame, areaicon, icon, etc etc

			// Indexes
			$table->index('niche_id', 'n');
			$table->index('type', 't');
			$table->index('level', 'l');
			$table->cascadeDeleteForeign('niches');
			$table->foreign('issuer_id')->references('id')->on('npcs')->onDelete('cascade');
			$table->foreign('target_id')->references('id')->on('npcs')->onDelete('cascade');
		});

		Schema::create('objective_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name');
			$table->string('description')->nullable();
		});

		Schema::create('item_objective', function (Blueprint $table) {
			// Build the basics of the pivot
			$table->pivot();

			// Additional Pivot Fields
			$table->boolean('reward')->nullable();
			// Qty given/required
			$table->unsignedInteger('quantity')->default(1);
			// Which quality is required?
			$table->unsignedTinyInteger('quality')->default(0);
			// Drop Rate.  Null == 100% // 50 == 50%
			$table->unsignedTinyInteger('rate')->nullable();

			// Indexes
			$table->index('reward', 'r');

			// Description of table
			// Used to handle both Objective Rewards and Requirements
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('item_objectives');
		Schema::dropIfExists('objective_translations');
		Schema::dropIfExists('objectives');
	}
}
