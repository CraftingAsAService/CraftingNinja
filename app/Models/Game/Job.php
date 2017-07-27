<?php

namespace App\Models\Game;

class Job extends BasicGameEntity
{

	public $translationModel = 'App\Models\Translations\JobTranslations';

	public $translatedAttributes = ['name', 'abbreviation', 'description'];

	protected $fillable = ['type', 'name', 'abbreviation', 'description'];

	/**
	 * Relationships
	 */

	// public function stats()
	// {
	// 	return $this->belongsToMany('App\Models\Game\Stat')->withPivot('weight');
	// }

	// public function items()
	// {
	// 	return $this->belongsToMany('App\Models\Game\Item');
	// }

}
