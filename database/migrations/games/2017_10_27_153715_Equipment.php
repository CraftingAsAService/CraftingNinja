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
			// Fields
			$table->increments('id');
			$table->unsignedInteger('item_id')->unique(); // FK Items, hasOne
			$table->unsignedInteger('niche_id')->default(0); // FK Niche ID, for a job set

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
			$table->unsignedTinyInteger('slot')->default(0); // FK to Individual Game Configuration
			$table->unsignedSmallInteger('level')->default(1);
			$table->unsignedTinyInteger('sockets')->default(0);

			$table->unique('item_id', 'i'); // Equipment is an extension of the items table, so there can only be one item_id reference per table
			$table->index('niche_id', 'n');
			$table->index('slot', 's');
			$table->index('level', 'l');
			$table->index('sockets', 'so');
			$table->cascadeDeleteForeign('items');
			$table->cascadeDeleteForeign('niches');
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
