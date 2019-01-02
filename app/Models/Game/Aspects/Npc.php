<?php

namespace App\Models\Game\Aspects;

use App\Models\Game\Aspect;
use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Shop;
use App\Models\Game\Aspects\Zone;
use App\Models\Game\Concepts\Detail;
use App\Models\Game\Translations\NpcTranslation;

class Npc extends Aspect
{

	public $translationModel = NpcTranslation::class;
	public $translatedAttributes = [ 'name' ];

	/**
	 * Scopes
	 */
	public function scopeMerchants($query)
	{
		// TODO
		// We only want npcs with a price_id in the pivot table
		// return $query->whereNotNull('price_id');
	}

	public function scopeEnemies($query)
	{
		return $query->whereEnemy(true);
	}

	/**
	 * Relationships
	 */

	public function zones()
	{
		return $this->morphToMany(Zone::class, 'coordinate')->withTranslation()->withPivot('x', 'y', 'z');
	}

	public function details()
	{
		return $this->morphMany(Detail::class, 'detail');
	}

	public function shops()
	{
		return $this->belongsToMany(Shop::class)->withTranslation();
	}

	public function items()
	{
		return $this->belongsToMany(Item::class)->withTranslation()->withPivot('rate');
	}

	public function prices()
	{
		// TODO
		// Utilize the price_id in the pivot table to pull this data
	}

}
