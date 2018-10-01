<?php

namespace App\Models\Game\Aspects;

class Node extends \App\Models\Game\Aspect
{

	public $translationModel = \App\Models\Game\Translations\NodeTranslation::class;
	public $translatedAttributes = ['name'];

	/**
	 * Relationships
	 */

	public function coordinates()
	{
		return $this->morphedByMany(\App\Models\Game\Concepts\Coordinate::class, 'coordinates');
	}

	public function details()
	{
		return $this->morphMany(\App\Models\Game\Concepts\Detail::class, 'details');
	}

}
