<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Jobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');

            // Admittedly, this would do better as a SET datatype in MySQL, but Laravel doesn't recognize it in the Schema builder.
            // I could do some workarounds for that, but it's not worth it; this gets the job done
            $table->enum('type', ['battle', 'crafting', 'gathering', 'hero']); // The Hero type can do everything
        });

        Schema::create('job_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_id')->unsigned(); // FK to jobs

            $table->string('locale')->index();

            $table->string('name')->default('Hero');
            $table->string('abbreviation')->default('hero');
            $table->string('description')->nullable();

            $table->unique(['job_id', 'locale']);
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['jobs', 'job_translations'] as $table)
            Schema::dropIfExists($table);
    }
}
