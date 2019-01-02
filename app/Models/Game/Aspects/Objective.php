<?php

namespace App\Models\Game\Aspects;

class Objective extends \App\Models\Game\Aspect
{

	public $translationModel = \App\Models\Game\Translations\ObjectiveTranslation::class;

	/**
	 * Relationships
	 */

	// public function jobs()
	// {
	// 	return $this->hasManyThrough(Job::class, \App\Models\Game\Concept\JobGroup::class, 'job_id', 'job_group_id', 'group_id');
	// }

	public function issuer()
	{
		return $this->belongsTo(Npc::class, 'issuer');
	}

	public function target()
	{
		return $this->belongsTo(Npc::class, 'target');
	}

	public function coordinates()
	{
		return $this->morphedByMany(\App\Models\Game\Concept\Coordinate::class, 'coordinates');
	}

	public function details()
	{
		return $this->morphMany(\App\Models\Game\Concept\Detail::class, 'details');
	}

}
