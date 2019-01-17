<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Reports extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reports', function (Blueprint $table) {
			// Fields
			$table->increments('id');
			$table->unsignedInteger('user_id'); // FK `users` in Primary DB
			$table->string('locale');
			$table->string('reason');
			$table->boolean('resolved')->default(false);

			// Polymorphic table
			$table->unsignedInteger('reportable_id');
			$table->string('reportable_type');

			$table->timestamps();

			// Indexes
			$table->index('user_id');
			// $table->cascadeDeleteForeign('users'); // Cannot include; Users exists on different schema
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('reports');
	}
}
