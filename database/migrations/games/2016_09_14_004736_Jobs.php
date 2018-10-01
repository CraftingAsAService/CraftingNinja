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

			// 18 chosen as it's twice the length of 'gathering', to allow for expansion
			// Expected values are 'battle', 'crafting', 'gathering', 'hero'; Hero being able to do everything (farming games, etc)
			$table->string('type', 18)->default('hero');

			// Gladiator is a tier 0 battle job
			// Paladin is a tier 1 battle job
			// Carpenter is a tier 0 crafting job
			// Miner is a tier 0 gathering job
			// Machinist is a tier 2 battle job, maybe tier 1, but it's really semanitics
			// I would have done a simple `boolean` of `advanced`, but the tier allows for odd use cases
			$table->tinyInteger('tier')->unsigned()->default(0);

			$table->index(['type']);
		});

		Schema::create('job_translations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned(); // FK to jobs

			$table->string('locale')->index();

			$table->string('name')->default('Hero');			// Gladiator
			$table->string('abbreviation')->default('hero');	// gld

			$table->unique(['job_id', 'locale']); // Each job can only have one name per locale
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
