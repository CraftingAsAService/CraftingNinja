<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Games extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Contains only language-neutral data.  game_translations available for name/etc
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug'); // ffxiv, wow, sos, dqh, rf4 // not a translatable thing, will be used to identify the game via subdomain or subdirectory
            $table->string('version')->default(null)->nullable(); // 99.99.99
            // $table->tinyInteger('currency_type')->unsigned(); // An identifier to distinguish how currency is.  Decimal'd?  Copper/Silver/Gold? Straight Gil/Yen system? In any event, all currency boils down to a single, non-decimal'd number. // TODO, move this into a config file

            $table->index('slug');
        });

        Schema::create('game_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id')->unsigned(); // FK to games

            $table->string('locale')->index();

            $table->string('name');
            $table->string('abbreviation');
            $table->string('description')->nullable();

            $table->unique(['game_id', 'locale']);
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['games', 'game_translations'] as $table)
            Schema::dropIfExists($table);
    }
}
