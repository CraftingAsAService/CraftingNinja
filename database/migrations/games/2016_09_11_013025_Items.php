<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Items extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Handled by Tags
        //  - Booleans
        //      - Uniqueness
        //      - Tradeable
        //      - Desynthable
        //      - Projectable
        //      - Crestworthy
        //      - Repairable
        //  - Rarity
        //  - Requirements
        //      - If a vendor or area is locked, a tag to explain that
        //          - Requires Exhalted
        //  - Cost "Feeling"
        //      - If a user thinks an item is too Expensive, or is a really good deal
        //          - Expensive
        //          - Cheap

        // Contains only language-neutral data.  item_translations available for name/etc
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');

            $table->tinyInteger('has_quality')->default(0); // Able to Quality.  Configurable per game (0 = Normal, 1 = HQ // 0  Normal, 1 = Silver Star, 2 = Gold Star)
            $table->smallInteger('level')->unsigned()->nullable();
            $table->smallInteger('ilvl')->unsigned()->nullable();
            $table->boolean('is_equipment')->default(0);
            $table->string('icon')->nullable(); // Stores both a "t/f" (or maybe MD5) or another identifier for some kind of Font Icon

            $table->index('is_equipment');
        });
        // Idea, but holding off on
        // TODO, document or something
        // identifier, VARCHAR A "maybe" column, used to tie it to an outside source.  Could use it's own table though.

        // TODO: How to handle Sockets?
        //   Equipment Mods?
        Schema::create('item_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned(); // FK to items

            $table->string('locale')->index();

            $table->string('name');
            $table->string('description')->nullable();

            $table->unique(['item_id', 'locale']);
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['items', 'item_translations'] as $table)
            Schema::dropIfExists($table);
    }
}
