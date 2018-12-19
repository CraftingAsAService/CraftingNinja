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
			$table->increments('id');
			// No values, just a placeholder for translations and other tables to reference
		});

		Schema::create('attribute_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('attribute_id')->unsigned(); // FK to attributes

			$table->string('locale')->index();

			$table->string('name');

			$table->unique(['attribute_id', 'locale']);
			$table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('attribute_translations');
		Schema::dropIfExists('attributes');
	}
}
