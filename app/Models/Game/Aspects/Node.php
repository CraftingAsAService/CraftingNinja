<?php

namespace App\Models\Game\Aspects;

use App\Models\Game\Aspect;
use App\Models\Game\Aspects\Zone;
use App\Models\Game\Concepts\Detail;
use App\Models\Game\Translations\NodeTranslation;

class Node extends Aspect
{

	public $translationModel = NodeTranslation::class;
	public $translatedAttributes = [ 'name' ];

	/**
	 * Relationships
	 */

	public function zones()
	{
		return $this->morphToMany(Zone::class, 'coordinate')->withTranslation()->withPivot('x', 'y', 'z');
	}

	public function details()
	{
		return $this->morphMany(Detail::class, 'details');
	}

}
