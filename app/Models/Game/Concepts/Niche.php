<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Aspects\Objective;
use App\Models\Game\Concept;
use App\Models\Game\Concepts\Equipment;
use Models\Game\Aspects\Job;

class Niche extends Concept
{
	/**
	 * A "Niche" is used as the word for a collection of jobs
	 * 	Equipment/etc can be used by multiple jobs, usually organized by "DPS", "Magic Users" or "Jobs that use pointy weapons"
	 * 	In this way, it's fulfilling a unique job clique/niche/combination
	 */

	/**
	 * Relationships
	 */

	public function jobs()
	{
		return $this->belongsToMany(Job::class)->withTranslation();
	}

	public function objectives()
	{
		return $this->hasMany(Objective::class)->withTranslation();
	}

	public function equipments()
	{
		return $this->hasMany(Equipment::class);
	}

	public function detail()
	{
		return $this->morphOne(Detail::class, 'detailable');
	}

}
