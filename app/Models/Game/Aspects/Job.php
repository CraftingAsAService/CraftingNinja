<?php

namespace App\Models\Game\Aspects;

use App\Models\Game\Aspect;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Listing;
use App\Models\Game\Concepts\Niche;
use App\Models\Game\Translations\JobTranslation;

class Job extends Aspect
{

	public $translationModel = JobTranslation::class;
	public $translatedAttributes = [ 'name', 'abbreviation' ];

	protected $appends = [ 'icon' ];

	/**
	 * Accessors & Mutators
	 */

	public function getIconAttribute()
	{
		return $this->translate('en')->abbreviation;
	}

	/**
	 * Scopes
	 */

	public function scopeByTypeAndTier()
	{
		return self::withTranslation()->whereNotIn('id', config('game.ignoreJobs'))->orderBy('tier')->get()->groupBy('type')->transform(function($item, $k) {
			return $item->groupBy('tier');
		});
	}

	/**
	 * Relationships
	 */

	public function listings()
	{
		return $this->hasMany(Listing::class)->withTranslation();
	}

	public function niches()
	{
		return $this->belongsToMany(Niche::class);
	}

	public function recipes()
	{
		return $this->hasMany(Recipe::class)->withTranslation();
	}

}
