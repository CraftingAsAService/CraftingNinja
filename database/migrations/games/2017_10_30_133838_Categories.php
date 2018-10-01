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
            $table->increments('id');

            $table->integer('category_id')->unsigned()->nullable(); // Self FK - Parent Category
        });

        Schema::create('category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned(); // FK to items
            $table->tinyInteger('rank')->unsigned()->default(0);

            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['category_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['categories', 'category_translations'] as $table)
            Schema::dropIfExists($table);
    }
}
