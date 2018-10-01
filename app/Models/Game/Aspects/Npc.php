<?php

namespace App\Models\Game\Aspects;

class Npc extends \App\Models\Game\Aspect
{

	public $translationModel = \App\Models\Game\Translations\NpcTranslation::class;

	/**
	 * Scopes
	 */
	public function scopeVendor($query)
	{
		return $query->whereNotNull('item_price_id');
	}

	public function scopeEnemy($query)
	{
		return $query->whereEnemy(true);
	}

	/**
	 * Relationships
	 */

	public function coordinates()
	{
		return $this->morphToMany(\App\Models\Game\Aspects\Zone::class, 'coordinate')->withPivot('x', 'y', 'z');
	}

	public function details()
	{
		return $this->morphMany(\App\Models\Game\Concepts\Detail::class, 'details');
	}

}
