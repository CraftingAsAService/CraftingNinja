<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Recipes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // A recipes name is usually the item it produces, but that's not always the case
        Schema::create('recipes', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('game_id')->unsigned(); // FK to games
            $table->integer('item_id')->unsigned(); // FK to items
            $table->integer('job_id')->unsigned(); // FK to jobs

            $table->smallInteger('level')->unsigned()->nullable(); // The recipes level, not an ilvl or an item's natural level

            $table->smallInteger('yield')->default(1)->unsigned(); // How many of this item the recipe produces
            $table->tinyInteger('quality')->default(0); // Quality reward type.  Configurable per game (0 = Normal, 1 = HQ // 0  Normal, 1 = Silver Star, 2 = Gold Star)
            $table->tinyInteger('chance')->unsigned()->nullable(); // Store 1 - 100. Assume 100 at Null. Chance that this item is produced.

            $table->index(['item_id', 'job_id', 'level']);
            // $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        Schema::create('recipe_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('recipe_id')->unsigned(); // FK to recipes

            $table->string('locale')->index();

            $table->string('name')->nullable();
            $table->string('description')->nullable();

            $table->unique(['recipe_id', 'locale']);
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
        });

        // Schema::create('item_recipe', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('item_id')->unsigned(); // FK to items
        //     $table->integer('recipe_id')->unsigned(); // FK to recipes

        //     $table->smallInteger('yield')->default(1)->unsigned(); // How many of this item the recipe produces
        //     $table->tinyInteger('quality')->default(0); // Quality reward type.  Configurable per game (0 = Normal, 1 = HQ // 0  Normal, 1 = Silver Star, 2 = Gold Star)
        //     $table->tinyInteger('chance')->unsigned()->nullable(); // Store 1 - 100. Assume 100 at Null. Chance that this item is produced.

        //     $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        //     $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['recipes', 'recipe_translations'/*, 'item_recipe'*/] as $table)
            Schema::dropIfExists($table);
    }
}
