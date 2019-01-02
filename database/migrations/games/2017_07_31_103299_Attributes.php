<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Attributes extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attributes', function (Blueprint $table) {
			// Fields
			$table->increments('id');
			// No direct values, just a placeholder for translations and other tables to reference
		});

		Schema::create('attribute_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name');
		});

		Schema::create('attribute_item', function (Blueprint $table) {
			// Build the basics of the pivot
			$table->pivot();

			// Additional Pivot Fields
			$table->unsignedTinyInteger('quality')->default(0); // For "HQ" items, some games are "None"/"Copper"/"Silver"/"Gold" star, etc
			$table->integer('value')->default(0); // Allow negatives

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
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('attribute_items');
		Schema::dropIfExists('attribute_translations');
		Schema::dropIfExists('attributes');
	}
}
