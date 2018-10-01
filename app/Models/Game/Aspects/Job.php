<?php

namespace App\Models\Game\Aspects;

class Job extends \App\Models\Game\Aspect
{

	public $translationModel = \App\Models\Game\Translations\JobTranslation::class;
	public $translatedAttributes = ['name', 'abbreviation'];

	protected $appends = ['icon'];

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

	public function getIconAttribute()
	{
		return $this->translate('en')->abbreviation;
	}

}
