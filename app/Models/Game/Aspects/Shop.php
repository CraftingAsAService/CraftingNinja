<?php

namespace App\Models\Game\Aspects;

use App\Models\Game\Aspect;
use App\Models\Game\Aspects\Npc;
use App\Models\Game\Aspects\Zone;
use App\Models\Game\Concepts\Detail;
use App\Models\Translations\ShopTranslation;

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

	public function detail()
	{
		return $this->morphOne(Detail::class, 'detailable');
	}

	public function zones()
	{
		return $this->morphToMany(Zone::class, 'coordinate')->withTranslation()->withPivot('x', 'y', 'z', 'radius');
	}

	public function npcs()
	{
		return $this->belongsToMany(Npc::class)->withTranslation();
	}

	/**
	 * Functions
	 */


}
