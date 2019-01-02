<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Details extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('details', function (Blueprint $table) {
			// Fields
			$table->increments('id');

			// Polymorphic table
			$table->unsignedInteger('detail_id');
			$table->string('detail_type');

			$table->json('data');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('details');
	}
}
