<?php

namespace App\Models\Game\Aspects;

use App\Models\Game\Aspect;
use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Npc;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Shop;
use App\Models\Game\Concepts\Coordinate;
use App\Models\Game\Translations\ZoneTranslation;

class Zone extends Aspect
{

	public $translationModel = ZoneTranslation::class;
	public $translatedAttributes = [ 'name' ];

	/**
	 * Relationships
	 */

	public function parent()
	{
		return $this->belongsTo(Zone::class);
	}

	public function items()
	{
		return $this->morphedByMany(Item::class, 'coordinate')->withTranslation();
	}

	public function nodes()
	{
		return $this->morphedByMany(Node::class, 'coordinate')->withTranslation();
	}

	public function npcs()
	{
		return $this->morphedByMany(Npc::class, 'coordinate')->withTranslation();
	}

	public function shops()
	{
		return $this->morphedByMany(Shop::class, 'coordinate')->withTranslation();
	}

	public function objectives()
	{
		return $this->morphedByMany(Objective::class, 'coordinate')->withTranslation();
	}

}
