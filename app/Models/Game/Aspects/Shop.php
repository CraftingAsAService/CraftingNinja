<?php

namespace App\Models\Game\Aspects;

use App\Models\Game\Aspect;
use App\Models\Game\Aspects\Npc;
use App\Models\Game\Translations\ShopTranslation;

class Shop extends Aspect
{

	public $translationModel = ShopTranslation::class;
	public $translatedAttributes = [ 'name' ];

	/**
	 * Scopes
	 */


	/**
	 * Relationships
	 */

	public function zones()
	{
		return $this->morphToMany(Zone::class, 'coordinate')->withTranslation()->withPivot('x', 'y', 'z');
	}

	public function npcs()
	{
		return $this->belongsToMany(Npc::class)->withTranslation();
	}

	public function items()
	{
		// TODO
		// Some kind of has many through Npc::class
	}

	/**
	 * Functions
	 */


}
