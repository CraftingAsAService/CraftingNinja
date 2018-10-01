<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Shops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Shops Exist
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
        });

        // NPCs own shops
        Schema::create('npc_shop', function (Blueprint $table) {
            $table->increments('id');

            // Shops are run by NPCs, but more than one NPC can run a store
            $table->integer('npc_id')->unsigned()->default(0); // FK NPCs
            $table->integer('shop_id')->unsigned()->default(0); // FK Shops

            // Shops can have Coordinates
        });

        // Shops will have a name
        Schema::create('shop_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id')->unsigned(); // FK to items

            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['shop_id', 'locale']);
        });

        // Shops have items, but really only superficially
        // Any meaningful item data belongs to the NPC who sells the item, not the shop that sells the item
        Schema::create('item_shop', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('item_id')->unsigned()->default(0); // FK Items
            $table->integer('shop_id')->unsigned()->default(0); // FK Shops

            $table->index('item_id');
            $table->index('shop_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
        foreach (['shops', 'npc_shop', 'shop_translations'] as $table)
            Schema::dropIfExists($table);
    }
}
