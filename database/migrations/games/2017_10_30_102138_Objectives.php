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
			$table->unsignedTinyInteger('type')->nullable();
			// Can you do this more than once?
			$table->boolean('repeatable')->nullable();
			// Level requirement?
			$table->unsignedTinyInteger('level')->nullable();
			$table->string('icon')->nullable();

			// Objectives can have Coordinates
			// Objectives can have Details - plate, frame, areaicon, icon, etc etc

			// Indexes
			$table->index('niche_id');
			$table->index('type');
			$table->index('level');
			$table->cascadeDeleteForeign('niches');
			$table->cascadeDeleteForeign('npcs', 'issuer_id');
			$table->cascadeDeleteForeign('npcs', 'target_id');
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
			$primaryFields = $table->pivot(false, null, null, false); // No ID column, but create our own primary key

			// Additional Pivot Fields
			$table->boolean('reward')->default(true); // True == Reward, False == Required
			// Qty given/required
			$table->unsignedInteger('quantity')->default(1);
			// Which quality is required?
			$table->unsignedTinyInteger('quality')->default(0);
			// Drop Rate.  Null == 100% // 50 == 50%
			$table->unsignedTinyInteger('rate')->nullable();

			// Indexes
			array_push($primaryFields, 'reward');
			$table->primary($primaryFields);

			$table->index('reward');

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
