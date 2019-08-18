<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Scrolls extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('scrolls', function (Blueprint $table) {
			// Fields
			$table->increments('id');
			$table->unsignedInteger('user_id'); // FK `users` in Primary DB
			$table->unsignedInteger('job_id')->nullable(); // FK `jobs`
			$table->unsignedSmallInteger('min_level')->nullable();
			$table->unsignedSmallInteger('max_level')->nullable();
			$table->timestamps();

			// Indexes
			$table->index('user_id');
			$table->index('job_id');
			$table->index('min_level');
			$table->index('max_level');
			// $table->cascadeDeleteForeign('users'); // Cannot include; Users exists on different schema
			$table->cascadeDeleteForeign('jobs');
		});

		Schema::create('scroll_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name');
			$table->string('description')->nullable();
		});

		Schema::create('jottings', function (Blueprint $table) {
			// Fields
			$table->increments('id');
			$table->unsignedInteger('scroll_id'); // FK to objectives

			// Polymorphic table
			$table->unsignedInteger('jotting_id');
			$table->string('jotting_type');

			$table->smallInteger('quantity')->default(1);

			// Indexes
			$table->index(['jotting_id', 'jotting_type']);
			$table->index('scroll_id');
			$table->cascadeDeleteForeign('scrolls');
		});

		Schema::create('votes', function (Blueprint $table) {
			// Fields
			$table->increments('id');
			$table->unsignedInteger('scroll_id'); // FK to objectives
			$table->unsignedInteger('user_id'); // FK to users

			$table->timestamps();

			// Indexes
			$table->index('user_id');
			$table->index('scroll_id');
			// $table->cascadeDeleteForeign('users'); // Cannot include; Users exist on different schema
			$table->cascadeDeleteForeign('scrolls');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('votes');
		Schema::dropIfExists('jottings');
		Schema::dropIfExists('scroll_translations');
		Schema::dropIfExists('scrolls');
	}
}
