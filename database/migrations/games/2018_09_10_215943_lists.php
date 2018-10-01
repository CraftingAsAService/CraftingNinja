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
		Schema::create('lists', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned(); // FK `users` in Primary DB
			$table->boolean('public')->default(0); // Searchable by public if true
			$table->boolean('active')->default(1); // The user's "active" list
			$table->string('name')->nullable(); // Required if public
			$table->string('description')->nullable();
			$table->json('contents');
			$table->timestamps();

			$table->index('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('lists');
	}
}
