<?php

namespace App\Models\Game\Aspects;

class Shop extends \App\Models\Game\Aspect
{

	public $translationModel = \App\Models\Game\Translations\ShopTranslation::class;
	public $translatedAttributes = ['name'];

	/**
	 * Scopes
	 */


	/**
	 * Relationships
	 */

	public function items()
	{
		return $this->belongsToMany(Item::class);
	}

	/**
	 * Functions
	 */


}