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
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index(); // FK `users` in Primary DB
			$table->integer('job_id')->unsigned()->nullable()->index(); // FK `jobs`
			$table->datetime('published_at')->nullable()->index(); // Searchable as a Book if true
			$table->timestamps();
		});

		Schema::create('listing_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('listing_id')->unsigned(); // FK to objectives

			$table->string('locale')->index();

			$table->string('name');
			$table->string('description')->nullable();

			$table->unique(['listing_id', 'locale']);
			$table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
		});

		Schema::create('listing_contents', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('listing_id')->unsigned()->index(); // FK to objectives

			$table->smallInteger('quantity');

			// Polymorphic table
			$table->integer('contents_id')->unsigned();
			$table->string('contents_type');

			$table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('listing_contents');
		Schema::dropIfExists('listing_translations');
		Schema::dropIfExists('listings');
	}
}
