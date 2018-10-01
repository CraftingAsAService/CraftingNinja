<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Nodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->increments('id');

            $table->smallInteger('level')->unsigned()->nullable();
            $table->tinyInteger('type')->unsigned()->nullable();

            // Nodes can have Coordinates
            // Nodes can have Details
        });

        Schema::create('node_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->unsigned(); // FK to nodes

            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['node_id', 'locale']);
        });

        Schema::create('node_rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->unsigned(); // FK to nodes
            $table->integer('item_id')->unsigned(); // FK to items

            $table->index('item_id');
            $table->index('node_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['nodes', 'node_translations'] as $table)
            Schema::dropIfExists($table);
    }
}
