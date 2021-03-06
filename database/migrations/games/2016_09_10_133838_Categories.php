<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Categories extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function (Blueprint $table) {
			// Fields
			$table->increments('id');
			$table->unsignedInteger('category_id')->nullable();
			$table->unsignedSmallInteger('rank')->nullable();

			// Indexes
			$table->index('category_id');
			$table->cascadeDeleteForeign($table->getTable()); // Self-referential
		});

		Schema::create('category_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('category_translations');
		Schema::dropIfExists('categories');
	}
}
