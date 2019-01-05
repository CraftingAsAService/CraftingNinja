<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Lists extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('listings', function (Blueprint $table) {
			// Fields
			$table->increments('id');
			$table->unsignedInteger('user_id'); // FK `users` in Primary DB
			$table->unsignedInteger('job_id')->nullable(); // FK `jobs`

			$table->datetime('published_at')->nullable(); // Searchable as a Book if true
			$table->timestamps();

			// Indexes
			$table->index('user_id');
			$table->index('job_id');
			$table->index('published_at');
			// $table->cascadeDeleteForeign('users'); // Cannot include; Users exists on different schema
			$table->cascadeDeleteForeign('jobs');
		});

		Schema::create('listing_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name');
			$table->string('description')->nullable();
		});

		Schema::create('jottings', function (Blueprint $table) {
			// Fields
			$table->increments('id');
			$table->unsignedInteger('listing_id'); // FK to objectives

			// Polymorphic table
			$table->unsignedInteger('jotting_id');
			$table->string('jotting_type');

			$table->smallInteger('quantity')->default(1);

			// Indexes
			$table->index(['jotting_id', 'jotting_type']);
			$table->index('listing_id');
			$table->cascadeDeleteForeign('listings');
		});

		Schema::create('votes', function (Blueprint $table) {
			// Fields
			$table->increments('id');
			$table->unsignedInteger('listing_id'); // FK to objectives
			$table->unsignedInteger('user_id'); // FK to users

			$table->timestamps();

			// Indexes
			$table->index('user_id');
			$table->index('listing_id');
			// $table->cascadeDeleteForeign('users'); // Cannot include; Users exist on different schema
			$table->cascadeDeleteForeign('listings');
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
		Schema::dropIfExists('listing_translations');
		Schema::dropIfExists('listings');
	}
}
