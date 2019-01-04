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
			// Fields
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

			// Indexes
			$table->index('type');
		});

		Schema::create('job_translations', function (Blueprint $table) {
			// Build the basics of the table
			$table->translatable();

			// Fields
			$table->string('name')->default('Hero');			// Gladiator
			$table->string('abbreviation')->default('hero');	// gld
		});

		Schema::create('niches', function(Blueprint $table) {
			// Fields
			$table->increments('id');

			// Description of table
			// Contains no real data, exists to support ManyToMany relationship
			// A niche is a collection of jobs; a piece of equipment may be equipable by 12 different jobs
			//  Those 12 are filling a niche
		});

		Schema::create('job_niche', function (Blueprint $table) {
			// Build the basics of the pivot
			$table->pivot();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('job_niches');
		Schema::dropIfExists('niches');
		Schema::dropIfExists('job_translations');
		Schema::dropIfExists('jobs');
	}
}
