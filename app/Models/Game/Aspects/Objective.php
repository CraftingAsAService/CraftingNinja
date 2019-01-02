<?php

namespace App\Models\Game\Aspects;

use App\Models\Game\Aspect;
use App\Models\Game\Aspects\Zone;
use App\Models\Game\Translations\ObjectiveTranslation;

class Objective extends Aspect
{

	public $translationModel = ObjectiveTranslation::class;
	public $translatedAttributes = [ 'name', 'description' ];

	/**
	 * Relationships
	 */

	public function zones()
	{
		return $this->morphToMany(Zone::class, 'coordinate')->withTranslation()->withPivot('x', 'y', 'z');
	}

	// public function jobs()
	// {
	// 	return $this->hasManyThrough(Job::class, \App\Models\Game\Concept\JobGroup::class, 'job_id', 'job_group_id', 'group_id');
	// }

	public function rewards()
	{
		return $this->belongsToMany(Item::class)->withTranslation()->withPivot('quantity', 'quality', 'rate')->wherePivot('reward', true);
	}

	public function requirements()
	{
		return $this->belongsToMany(Item::class)->withTranslation()->withPivot('quantity', 'quality', 'rate')->wherePivot('reward', false);
	}

	public function issuer()
	{
		return $this->belongsTo(Npc::class, 'issuer');
	}

	public function target()
	{
		return $this->belongsTo(Npc::class, 'target');
	}

	public function details()
	{
		return $this->morphMany(\App\Models\Game\Concept\Detail::class, 'details');
	}

}
