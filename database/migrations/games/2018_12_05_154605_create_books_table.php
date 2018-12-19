<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('books', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned(); // FK `users` in Primary DB
			$table->string('title');
			$table->string('locale')->index();
			$table->timestamps();
			$table->index('user_id');
		});

		Schema::create('book_item', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('book_id')->unsigned(); // FK `books`
			$table->integer('item_id')->unsigned(); // FK `items`
			$table->smallInteger('quantity')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('books');
	}
}
