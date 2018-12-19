<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobGroups extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('groups', function(Blueprint $table) {
			$table->increments('id');
			// Has no real data, exists to support ManyToMany relationship
		});

		Schema::create('job_groups', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('group_id')->unsigned()->default(0)->index(); // FK groups
			$table->integer('job_id')->unsigned()->default(0)->index(); // FK jobs

			$table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
			$table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('job_groups');
		Schema::dropIfExists('groups');
	}
}
