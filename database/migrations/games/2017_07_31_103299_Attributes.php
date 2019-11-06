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
			$table->unsignedSmallInteger('rank')->nullable();
		});

		Schema::create('attribute_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name');
			$table->text('description')->nullable();
		});

		Schema::create('attribute_item', function (Blueprint $table) {
			// Build the basics of the pivot
			$primaryFields = $table->pivot(false, null, null, false); // No ID column, but create our own primary key

			// Additional Pivot Fields
			$table->unsignedTinyInteger('quality')->default(0); // For "HQ" items, some games are "None"/"Copper"/"Silver"/"Gold" star, etc
			$table->decimal('value', 8, 2)->default(0); // Allow negatives

			array_push($primaryFields, 'quality');
			$this->primary($primaryFields);
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
