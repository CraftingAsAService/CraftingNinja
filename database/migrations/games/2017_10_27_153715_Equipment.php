<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Equipment extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('equipment', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('item_id')->unsigned()->unique(); // FK Items, hasOne

			// Equipment Related
			// For ffxiv, every equippable item has a job related to it
			//   likewise, only items that have jobs attached are equipment
			// It's possible that unequipable items could be tied to a job...
			// But in terms of crafting things, I'm not sure that information is helpful or useful, you're not "using" the item, you just have it on your person to craft with. So, IGNORE the possibility of normal items having job specifications
			// The same goes for elvl, items may have a level usage requirement, but you're not "using" the item
			// IGNORE The concept of set pieces and thus attribute bonuses for those sets
			// IGNORE the concept of pieces belonging to a faction
			// "equip": 1, // Equipable
			// "jobs": 68, // ID of Combination of Jobs that can equip this
			// "slot": 13, // Equipable space, store this as-is.  Some games aren't as complicated and this may just be a duplicate of Category ID, which is fine
			// "elvl": 49, // Required Level
			// "sockets": 2, // Number of materia sockets
			$table->integer('job_group_id')->unsigned()->default(0)->index(); // FK JobSets Group IDs, not the ID of the table itself
			$table->tinyInteger('slot')->unsigned()->default(0)->index(); // FK to Individual Game Configuration
			$table->smallInteger('level')->unsigned()->default(1)->index();
			$table->tinyInteger('sockets')->unsigned()->default(0)->index();

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
		Schema::dropIfExists('equipment');
	}
}
