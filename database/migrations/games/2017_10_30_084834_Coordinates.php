<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Coordinates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coordinates', function (Blueprint $table) {
            $table->increments('id');

            // Many to Many Polymorphic table
            $table->integer('zone_id')->unsigned()->default(0); // FK Zones
            $table->integer('coordinate_id')->unsigned();
            $table->string('coordinate_type');

            // A lot of different things (NPCs, Shops, mining/gathering nodes, quests, etc, etc) can exist in a zone at a specific spot
            $table->string('x')->nullable(); // X Coordinate
            $table->string('y')->nullable(); // Y Coordinate
            $table->string('z')->nullable();

            $table->index(['coordinate_type', 'coordinate_id']);
            $table->index('zone_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coordinates');
    }
}
