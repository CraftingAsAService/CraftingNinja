<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ItemAttributes extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('item_attribute', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('item_id')->unsigned(); // FK to items
			$table->integer('attribute_id')->unsigned(); // FK to items

			// Both normal items, and equipable items have stats
			// Equipment example
			// "attr": {
			// 	"Physical Damage": 32,
			// 	"Magic Damage": 50,
			// 	"Delay": 3.2,
			// 	"Intelligence": 12,
			// 	"Mind": 12,
			// 	"Vitality": 10,
			// 	"Critical Hit": 18
			// },
			// "attr_hq": {
			// 	"Physical Damage": 36,
			// 	"Magic Damage": 56,
			// 	"Intelligence": 13,
			// 	"Mind": 13,
			// 	"Vitality": 12,
			// 	"Critical Hit": 23
			// },
			// Food example
			// "attr": {
			// 	"action": {
			// 		"Spell Speed": {
			// 			"rate": 4, // Percentage of point improvement
			// 			"limit": 51 // Up to a total of 51 points more // IGNORE, out of scope
			// 		},
			// 		"Vitality": {
			// 			"rate": 3,
			// 			"limit": 52
			// 		},
			// 		"Piety": {
			// 			"rate": 2,
			// 			"limit": 13
			// 		}
			// 	}
			// },
			// "attr_hq": {
			// 	"action": {
			// 		"Spell Speed": {
			// 			"rate": 5,
			// 			"limit": 64
			// 		},
			// 		"Vitality": {
			// 			"rate": 4,
			// 			"limit": 65
			// 		},
			// 		"Piety": {
			// 			"rate": 2,
			// 			"limit": 16
			// 		}
			// 	}
			// },

			$table->tinyInteger('quality')->unsigned()->default(0); // For "HQ" items, some games are "None"/"Copper"/"Silver"/"Gold" star, etc
			$table->integer('value')->default(0); // Allow negatives
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('item_attribute');
	}
}
